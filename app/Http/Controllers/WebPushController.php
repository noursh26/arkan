<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebPushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class WebPushController extends Controller
{
    /**
     * Subscribe to Web Push notifications
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|url',
            'publicKey' => 'required|string',
            'authToken' => 'required|string',
            'encoding' => 'nullable|string|in:aesgcm,aes128gcm',
        ]);

        $userId = auth()->id();

        WebPushSubscription::updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            [
                'public_key' => $validated['publicKey'],
                'auth_token' => $validated['authToken'],
                'encoding' => $validated['encoding'] ?? 'aesgcm',
                'user_id' => $userId,
            ]
        );

        return response()->json(['success' => true, 'message' => 'تم الاشتراك بنجاح']);
    }

    /**
     * Unsubscribe from Web Push notifications
     */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|url',
        ]);

        WebPushSubscription::where('endpoint', $validated['endpoint'])->delete();

        return response()->json(['success' => true, 'message' => 'تم إلغاء الاشتراك']);
    }

    /**
     * Get VAPID public key
     */
    public function vapidKey()
    {
        return response()->json([
            'publicKey' => config('services.webpush.vapid_public_key'),
        ]);
    }

    /**
     * Send Web Push notification to admin users
     */
    public function sendToAdmins(string $title, string $body, array $data = []): array
    {
        $vapid = [
            'subject' => config('services.webpush.subject'),
            'publicKey' => config('services.webpush.vapid_public_key'),
            'privateKey' => config('services.webpush.vapid_private_key'),
        ];

        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        // Process subscriptions in chunks to avoid memory exhaustion
        WebPushSubscription::chunk(100, function ($subscriptions) use ($vapid, $title, $body, $data, &$results) {
            $webPush = new WebPush(['VAPID' => $vapid]);

            foreach ($subscriptions as $sub) {
                $subscription = Subscription::create([
                    'endpoint' => $sub->endpoint,
                    'publicKey' => $sub->public_key,
                    'authToken' => $sub->auth_token,
                    'contentEncoding' => $sub->encoding,
                ]);

                $payload = json_encode([
                    'title' => $title,
                    'body' => $body,
                    'icon' => '/icon-192x192.png',
                    'badge' => '/badge-72x72.png',
                    'data' => $data,
                    'requireInteraction' => true,
                ]);

                $webPush->queueNotification($subscription, $payload);
            }

            foreach ($webPush->flush() as $report) {
                if ($report->isSuccess()) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = $report->getReason();

                    // Remove invalid subscriptions
                    if ($report->isSubscriptionExpired()) {
                        WebPushSubscription::where('endpoint', $report->getEndpoint())->delete();
                    }
                }
            }
        });

        return $results;
    }
}
