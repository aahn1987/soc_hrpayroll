<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FcmService
{
    /**
     * Send push notification via Firebase Cloud Messaging (FCM)
     *
     * @param string $token  // FCM device token
     * @param string $title  // Notification title
     * @param string $body   // Notification body message
     * @return array         // Response from FCM
     */
    public static function sendNotification($token, $title, $body)
    {
        $serverKey = config('services.fcm.server_key');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'priority' => 'high',
                ]);

        return $response->json();
    }
}
