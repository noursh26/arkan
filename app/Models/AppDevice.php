<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppDevice extends Model
{
    protected $fillable = ['device_id', 'player_id', 'platform', 'app_version', 'last_seen_at'];
    protected $casts = ['last_seen_at' => 'datetime'];
}
