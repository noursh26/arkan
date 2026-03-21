<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use App\Models\DailyNotification;

class DailyNotificationController extends ApiController
{
    public function today()
    {
        $data = Cache::remember('today_notification_' . today()->format('Y-m-d'), now()->secondsUntilEndOfDay(), function () {
            $notif = DailyNotification::where('is_active', true)
                ->where(fn($q) => $q->whereDate('scheduled_date', today())->orWhereNull('scheduled_date'))
                ->inRandomOrder()
                ->first();

            if (!$notif) return null;

            return ['id' => $notif->id, 'title' => $notif->title, 'body' => $notif->body, 'type' => $notif->type];
        });

        return $this->success($data);
    }
}
