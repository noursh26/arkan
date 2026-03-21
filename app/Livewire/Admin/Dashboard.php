<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AppDevice;
use App\Models\DailyNotification;
use App\Models\Dhikr;
use App\Models\IslamicRuling;
use App\Models\NotificationLog;
use App\Models\MotivationalMessage;

class Dashboard extends Component
{
    public function render()
    {
        // Stats Cards
        $totalDevices = AppDevice::count();
        $notificationsThisWeek = NotificationLog::where('sent_at', '>=', now()->subDays(7))->count();
        $totalActiveAdhkar = Dhikr::where('is_active', true)->count();
        $totalRulings = IslamicRuling::count();
        
        // Recent Activity - Last 10 devices
        $recentDevices = AppDevice::latest()->limit(10)->get();
        
        // Quick Actions Data
        $messagesCount = MotivationalMessage::count();
        $rulingsCount = IslamicRuling::count();
        $upcomingNotifications = DailyNotification::where('is_sent', false)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('scheduled_date')
                      ->orWhere('scheduled_date', '>=', now()->format('Y-m-d'));
            })
            ->count();
        
        return view('livewire.admin.dashboard', [
            'stats' => [
                'total_devices' => $totalDevices,
                'notifications_this_week' => $notificationsThisWeek,
                'total_adhkar' => $totalActiveAdhkar,
                'total_rulings' => $totalRulings,
            ],
            'recent_devices' => $recentDevices,
            'quick_actions' => [
                'messages_count' => $messagesCount,
                'rulings_count' => $rulingsCount,
                'upcoming_notifications' => $upcomingNotifications,
            ],
        ])->layout('layouts.admin', ['page_title' => 'لوحة التحكم']);
    }
}
