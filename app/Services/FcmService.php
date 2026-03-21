<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AppDevice;

class FcmService
{
    private string $serverKey;
    private string $projectId;
    private string $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

    public function __construct()
    {
        $this->serverKey = config('services.fcm.server_key', '');
        $this->projectId = config('services.fcm.project_id', '');
    }

    public function sendToAll(string $title, string $body, array $data = []): array
    {
        $tokens = AppDevice::pluck('fcm_token')->toArray();

        if (empty($tokens)) {
            Log::info('FCM: No devices registered, skipping send.');
            return ['success' => true, 'success_count' => 0, 'failure_count' => 0];
        }

        $successCount = 0;
        $failureCount = 0;

        $chunks = array_chunk($tokens, 1000);

        foreach ($chunks as $chunk) {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type'  => 'application/json',
            ])->post($this->fcmUrl, [
                'registration_ids' => $chunk,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                    'sound' => 'default',
                ],
                'data' => $data,
            ]);

            if ($response->failed()) {
                Log::error('FCM send failed', [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
                $failureCount += count($chunk);
                continue;
            }

            $result = $response->json();
            $successCount += $result['success'] ?? 0;
            $failureCount += $result['failure'] ?? 0;
        }

        return [
            'success'       => true,
            'success_count' => $successCount,
            'failure_count' => $failureCount,
        ];
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $this->serverKey,
            'Content-Type'  => 'application/json',
        ])->post($this->fcmUrl, [
            'to'           => $token,
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ]);

        if ($response->failed()) {
            Log::error('FCM sendToToken failed', [
                'token'    => substr($token, 0, 20) . '...',
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
            return false;
        }

        return $response->successful();
    }

    public function sendToTopic(string $topic, string $title, string $body, array $data = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $this->serverKey,
            'Content-Type'  => 'application/json',
        ])->post($this->fcmUrl, [
            'to'           => '/topics/' . $topic,
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],
            'data' => $data,
        ]);

        if ($response->failed()) {
            Log::error('FCM sendToTopic failed', [
                'topic'    => $topic,
                'response' => $response->body(),
            ]);
            return ['success' => false, 'error' => $response->body()];
        }

        $result = $response->json();
        return [
            'success'       => true,
            'message_id'    => $result['message_id'] ?? null,
        ];
    }
}
