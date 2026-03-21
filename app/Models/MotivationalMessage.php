<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotivationalMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['text', 'prayer_time', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function getPrayerTimeLabelAttribute(): string
    {
        return match($this->prayer_time) {
            'fajr'    => 'الفجر',
            'dhuhr'   => 'الظهر',
            'asr'     => 'العصر',
            'maghrib' => 'المغرب',
            'isha'    => 'العشاء',
            default   => 'الكل',
        };
    }
}
