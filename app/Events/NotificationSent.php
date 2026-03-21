<?php

namespace App\Events;

use App\Models\NotificationLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public NotificationLog $log;
    public string $title;

    public function __construct(NotificationLog $log, string $title)
    {
        $this->log = $log;
        $this->title = $title;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('admin-notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'notification.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'notification_id' => $this->log->notification_id,
            'title' => $this->title,
            'devices_count' => $this->log->devices_count,
            'success_count' => $this->log->success_count,
            'sent_at' => $this->log->sent_at->toIso8601String(),
            'message' => 'تم إرسال إشعار جديد',
        ];
    }
}
