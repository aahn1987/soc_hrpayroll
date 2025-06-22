<?php
namespace App\Services;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\EmpTokens;

class FcmService
{
    protected static $accessToken;

    /**
     * الحصول على توكن الوصول (Access Token) من ملف الخدمة
     */
    public static function getAccessToken()
    {
        if (self::$accessToken) {
            return self::$accessToken;
        }

        $serviceAccountPath = base_path(config('fcm.service_account_json'));
        Log::info("FCM Service Account Path: $serviceAccountPath");

        // تحقق من وجود الملف
        if (!file_exists($serviceAccountPath)) {
            Log::error("FCM Service Account JSON file not found at: $serviceAccountPath");
            return null;
        }

        // تحقق من أنه ملف وليس مجلد
        if (is_dir($serviceAccountPath)) {
            Log::error("Expected file but found directory at: $serviceAccountPath");
            return null;
        }

        // قراءة محتوى الملف
        $serviceAccountContent = file_get_contents($serviceAccountPath);

        if ($serviceAccountContent === false) {
            Log::error("Failed to read the service account file content at: $serviceAccountPath");
            return null;
        }

        // فك تشفير JSON والتأكد من صحته
        $serviceAccount = json_decode($serviceAccountContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Invalid JSON in service account file: " . json_last_error_msg());
            return null;
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($serviceAccount);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->setAccessType('offline');
            $client->refreshTokenWithAssertion();

            self::$accessToken = $client->getAccessToken()['access_token'];
            Log::info("FCM Access Token successfully obtained.");
            return self::$accessToken;
        } catch (\Exception $e) {
            Log::error("Exception while getting FCM access token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * إرسال إشعارات دفعة إلى مجموعة من التوكينات
     */
    public static function sendNotificationBatch(array $tokens, string $title, string $body)
    {
        $accessToken = self::getAccessToken();

        if (!$accessToken) {
            Log::error("No access token available for FCM V1.");
            return false;
        }

        $serviceAccountPath = base_path(config('fcm.service_account_json'));
        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
        $projectId = $serviceAccount['project_id'] ?? config('fcm.project_id');

        if (!$projectId) {
            Log::error("Project ID not found in service account JSON or .env file.");
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        foreach ($tokens as $token) {
            $payload = [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                    ],
                    "android" => [
                        "priority" => "high",
                    ],
                    "apns" => [
                        "headers" => [
                            "apns-priority" => "10"
                        ],
                    ],
                ]
            ];

            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer $accessToken",
                    'Content-Type' => 'application/json',
                ])->post($url, $payload);

                if ($response->failed()) {
                    Log::error("FCM send failed: " . $response->body());
                } else {
                    Log::info("FCM notification sent successfully to token: $token");
                }
            } catch (\Exception $e) {
                Log::error("Exception while sending FCM message: " . $e->getMessage());
            }
        }

        Log::info("FCM notifications sent to " . count($tokens) . " tokens.");

        return true;
    }
    public static function sendNotificationSingle(string $token, string $title, string $body)
    {
        $accessToken = self::getAccessToken();

        if (!$accessToken) {
            Log::error("No access token available for FCM V1.");
            return false;
        }

        $serviceAccountPath = base_path(config('fcm.service_account_json'));
        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
        $projectId = $serviceAccount['project_id'] ?? config('fcm.project_id');

        if (!$projectId) {
            Log::error("Project ID not found in service account JSON or .env file.");
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";



        $url = "https://fcm.googleapis.com/fcm/send";

        $payload = [
            "to" => $token,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default"
            ],
            "priority" => "high",
        ];

        $response = Http::withHeaders([
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        if ($response->failed()) {
            Log::error("FCM single send failed: " . $response->body());
            return false;
        }

        $responseData = $response->json();

        if (isset($responseData['error'])) {
            if (in_array($responseData['error'], ['InvalidRegistration', 'NotRegistered'])) {
                EmpTokens::where('fcm_token', $token)->delete();
                Log::info("Deleted invalid token: $token");
            } else {
                Log::warning("FCM token error for $token: " . $responseData['error']);
            }
        }

        Log::info("FCM notification sent to token: $token");

        return true;
    }
}
