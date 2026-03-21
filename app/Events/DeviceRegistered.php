<?php

namespace App\Events;

use App\Models\AppDevice;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AppDevice $device;
    public int $totalDevices;

    public function __construct(AppDevice $device)
    {
        $this->device = $device;
        $this->totalDevices = AppDevice::count();
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('admin-notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'device.registered';
    }

    public function broadcastWith(): array
    {
        return [
            'device_id' => $this->device->device_id,
            'platform' => $this->device->platform,
            'total_devices' => $this->totalDevices,
            'message' => 'جهاز جديد تم تسجيله',
        ];
    }
}
