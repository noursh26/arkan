<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyNotification extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'body', 'type', 'scheduled_date', 'send_time', 'is_sent', 'sent_at', 'is_active'];
    protected $casts = [
        'is_sent'        => 'boolean',
        'is_active'      => 'boolean',
        'scheduled_date' => 'date',
        'sent_at'        => 'datetime',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(NotificationLog::class, 'notification_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'khulq'    => 'خلق',
            'nafl'     => 'نافلة',
            'dua'      => 'دعاء',
            default    => 'تذكير',
        };
    }
}
