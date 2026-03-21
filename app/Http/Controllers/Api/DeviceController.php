<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AppDevice;
use App\Events\DeviceRegistered;

class DeviceController extends ApiController
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'device_id'   => 'required|string|max:255',
            'player_id'   => 'required|string',
            'platform'    => 'required|in:android,ios',
            'app_version' => 'nullable|string|max:20',
        ]);

        $device = AppDevice::updateOrCreate(
            ['device_id' => $data['device_id']],
            array_merge($data, ['last_seen_at' => now()])
        );

        // Broadcast real-time event to admin panel
        event(new DeviceRegistered($device));

        return $this->success(['registered' => true]);
    }
}
