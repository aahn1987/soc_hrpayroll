<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\EmpTokens;

class FcmService
{
    protected static $serverKey;

    public static function init()
    {
        self::$serverKey = env('FCM_SERVER_KEY');
    }

    public static function sendNotificationBatch(array $tokens, string $title, string $body)
    {
        self::init();

        $url = "https://fcm.googleapis.com/fcm/send";

        $payload = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default"
            ],
            "priority" => "high",
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . self::$serverKey,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        if ($response->failed()) {
            Log::error("FCM batch send failed: " . $response->body());
            return false;
        }

        $responseData = $response->json();

        if (isset($responseData['results'])) {
            foreach ($responseData['results'] as $index => $result) {
                if (isset($result['error'])) {
                    $error = $result['error'];
                    $badToken = $tokens[$index];

                    if (in_array($error, ['InvalidRegistration', 'NotRegistered'])) {
                        EmpTokens::where('fcm_token', $badToken)->delete();
                        Log::info("Deleted invalid token: $badToken");
                    } else {
                        Log::warning("FCM token error for $badToken: $error");
                    }
                }
            }
        }

        Log::info("FCM batch notification sent to " . count($tokens) . " tokens.");

        return true;
    }
    public static function sendNotificationSingle(string $token, string $title, string $body)
    {
        self::init();

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
            'Authorization' => 'key=' . self::$serverKey,
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
