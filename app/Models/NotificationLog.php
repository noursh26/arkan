<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    protected $fillable = ['notification_id', 'devices_count', 'success_count', 'failure_count', 'sent_at'];
    protected $casts = ['sent_at' => 'datetime'];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(DailyNotification::class, 'notification_id');
    }
}
