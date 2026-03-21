<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebPushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'public_key',
        'auth_token',
        'encoding',
        'user_id',
    ];

    protected $hidden = [
        'auth_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
