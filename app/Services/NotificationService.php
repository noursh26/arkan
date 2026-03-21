<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\AppDevice;
use Berkayk\OneSignal\OneSignalClient;

class NotificationService
{
    private OneSignalClient $oneSignal;

    public function __construct()
    {
        $this->oneSignal = new OneSignalClient(
            config('services.onesignal.app_id'),
            config('services.onesignal.rest_api_key'),
            config('services.onesignal.user_auth_key', '')
        );
    }

    /**
     * Send notification to all registered devices
     */
    public function sendToAll(string $title, string $body, array $data = []): array
    {
        try {
            $playerIds = AppDevice::pluck('player_id')->filter()->toArray();

            if (empty($playerIds)) {
                Log::info('OneSignal: No devices registered, skipping send.');
                return ['success' => true, 'success_count' => 0, 'failure_count' => 0];
            }

            $params = [
                'headings' => ['en' => $title, 'ar' => $title],
                'contents' => ['en' => $body, 'ar' => $body],
                'include_player_ids' => $playerIds,
                'data' => $data,
                'priority' => 10,
            ];

            $response = $this->oneSignal->sendNotificationCustom($params);

            // OneSignal 'recipients' = targeted devices, not successful deliveries
            // Use errors array to determine actual failures
            $errors = $response['errors'] ?? [];
            $invalidPlayerIds = $response['invalid_player_ids'] ?? [];
            $totalTargeted = count($playerIds);
            $failureCount = count($errors) + count($invalidPlayerIds);
            $successCount = max(0, $totalTargeted - $failureCount);

            return [
                'success' => true,
                'success_count' => $successCount,
                'failure_count' => $failureCount,
            ];
        } catch (\Exception $e) {
            Log::error('OneSignal sendToAll failed', [
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'success_count' => 0,
                'failure_count' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send notification to a single device
     */
    public function sendToDevice(string $playerId, string $title, string $body, array $data = []): bool
    {
        try {
            $params = [
                'headings' => ['en' => $title, 'ar' => $title],
                'contents' => ['en' => $body, 'ar' => $body],
                'include_player_ids' => [$playerId],
                'data' => $data,
            ];

            $response = $this->oneSignal->sendNotificationCustom($params);

            return isset($response['recipients']) && $response['recipients'] > 0;
        } catch (\Exception $e) {
            Log::error('OneSignal sendToDevice failed', [
                'player_id' => $playerId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send notification to a segment
     */
    public function sendToSegment(string $segment, string $title, string $body, array $data = []): array
    {
        try {
            $params = [
                'headings' => ['en' => $title, 'ar' => $title],
                'contents' => ['en' => $body, 'ar' => $body],
                'included_segments' => [$segment],
                'data' => $data,
            ];

            $response = $this->oneSignal->sendNotificationCustom($params);

            return [
                'success' => true,
                'recipients' => $response['recipients'] ?? 0,
            ];
        } catch (\Exception $e) {
            Log::error('OneSignal sendToSegment failed', [
                'segment' => $segment,
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
