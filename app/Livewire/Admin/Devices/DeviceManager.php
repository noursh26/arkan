<?php

namespace App\Livewire\Admin\Devices;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AppDevice;

class DeviceManager extends Component
{
    use WithPagination;

    // Filters
    public $platformFilter = '';
    public $search = '';
    
    protected $queryString = ['platformFilter', 'search'];
    
    public function updatingPlatformFilter()
    {
        $this->resetPage();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $query = AppDevice::query();
        
        if ($this->platformFilter) {
            $query->where('platform', $this->platformFilter);
        }
        
        if ($this->search) {
            $query->where('device_id', 'like', '%' . $this->search . '%');
        }
        
        $devices = $query->latest()->paginate(15);
        
        // Stats
        $totalDevices = AppDevice::count();
        $androidCount = AppDevice::where('platform', 'android')->count();
        $iosCount = AppDevice::where('platform', 'ios')->count();
        $activeLast7Days = AppDevice::where('last_seen_at', '>=', now()->subDays(7))->count();
        
        return view('livewire.admin.devices.device-manager', [
            'devices' => $devices,
            'stats' => [
                'total' => $totalDevices,
                'android' => $androidCount,
                'ios' => $iosCount,
                'active_7_days' => $activeLast7Days,
            ],
        ])->layout('layouts.admin', ['page_title' => 'الأجهزة المسجلة']);
    }
}
