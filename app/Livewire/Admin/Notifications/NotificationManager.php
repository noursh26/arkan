<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DailyNotification;
use App\Models\NotificationLog;
use App\Services\NotificationService;
use App\Http\Controllers\WebPushController;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\Cache;

class NotificationManager extends Component
{
    use WithPagination;

    public $activeTab = 'upcoming'; // 'upcoming' or 'sent'
    
    // Form Fields
    public $notificationId = null;
    public $title = '';
    public $body = '';
    public $type = 'khulq';
    public $scheduledDate = '';
    public $sendTime = '07:00';
    public $isActive = true;
    
    // Filters
    public $typeFilter = '';
    public $isSentFilter = '';
    
    // Modals
    public $showModal = false;
    public $showSendModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;
    public $sendNowId = null;
    
    protected $queryString = ['activeTab', 'typeFilter', 'isSentFilter'];
    
    protected function rules()
    {
        return [
            'title' => 'required|string|max:100',
            'body' => 'required|string|max:300',
            'type' => 'required|in:khulq,nafl,dua,reminder',
            'scheduledDate' => 'nullable|date',
            'sendTime' => 'required',
            'isActive' => 'boolean',
        ];
    }
    
    protected function messages()
    {
        return [
            'title.required' => 'عنوان الإشعار مطلوب',
            'title.max' => 'العنوان يجب ألا يتجاوز 100 حرف',
            'body.required' => 'نص الإشعار مطلوب',
            'body.max' => 'نص الإشعار يجب ألا يتجاوز 300 حرف',
            'type.required' => 'نوع الإشعار مطلوب',
            'type.in' => 'نوع الإشعار غير صالح',
            'sendTime.required' => 'وقت الإرسال مطلوب',
        ];
    }
    
    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    
    public function edit($id)
    {
        $notification = DailyNotification::findOrFail($id);
        $this->notificationId = $notification->id;
        $this->title = $notification->title;
        $this->body = $notification->body;
        $this->type = $notification->type;
        $this->scheduledDate = $notification->scheduled_date?->format('Y-m-d');
        $this->sendTime = $notification->send_time;
        $this->isActive = $notification->is_active;
        $this->showModal = true;
    }
    
    public function save()
    {
        $validated = $this->validate();
        
        $data = [
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'scheduled_date' => $this->scheduledDate ?: null,
            'send_time' => $this->sendTime,
            'is_active' => $this->isActive,
        ];
        
        if ($this->notificationId) {
            $notification = DailyNotification::findOrFail($this->notificationId);
            $notification->update($data);
            $toastMessage = 'تم تحديث الإشعار بنجاح';
        } else {
            DailyNotification::create($data);
            $toastMessage = 'تم إضافة الإشعار بنجاح';
        }
        
        $this->showModal = false;
        $this->resetForm();
        
        // Clear today's notification cache to reflect changes in API
        Cache::forget('today_notification_' . now()->format('Y-m-d'));
        
        $this->dispatch('toast', ['type' => 'success', 'message' => $toastMessage]);
    }
    
    public function confirmSendNow($id)
    {
        $this->sendNowId = $id;
        $this->showSendModal = true;
    }
    
    public function sendNow(NotificationService $notificationService)
    {
        if (!$this->sendNowId) {
            return;
        }
        
        $notification = DailyNotification::findOrFail($this->sendNowId);
        
        try {
            // Send via OneSignal to mobile devices
            $result = $notificationService->sendToAll(
                $notification->title,
                $notification->body,
                ['type' => $notification->type, 'notification_id' => $notification->id]
            );
            
            // Send Web Push to admin browsers
            $webPush = new WebPushController();
            $webPush->sendToAdmins(
                'إشعار جديد: ' . $notification->title,
                $notification->body,
                ['url' => route('admin.notifications')]
            );
            
            // Log the notification
            $log = NotificationLog::create([
                'notification_id' => $notification->id,
                'devices_count' => $result['success_count'] + $result['failure_count'],
                'success_count' => $result['success_count'],
                'failure_count' => $result['failure_count'],
                'sent_at' => now(),
            ]);
            
            // Broadcast real-time event
            event(new NotificationSent($log, $notification->title));
            
            // Mark as sent
            $notification->update(['is_sent' => true, 'sent_at' => now()]);
            
            $this->showSendModal = false;
            $this->sendNowId = null;
            
            $this->dispatch('toast', [
                'type' => 'success', 
                'message' => "تم إرسال الإشعار بنجاح إلى {$result['success_count']} جهاز"
            ]);
        } catch (\Exception $e) {
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'حدث خطأ أثناء إرسال الإشعار: ' . $e->getMessage()
            ]);
        }
    }
    
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    
    public function delete()
    {
        if ($this->deleteId) {
            DailyNotification::findOrFail($this->deleteId)->delete();
            Cache::forget('today_notification_' . now()->format('Y-m-d'));
            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->dispatch('toast', ['type' => 'success', 'message' => 'تم حذف الإشعار بنجاح']);
        }
    }
    
    public function toggleActive($id)
    {
        $notification = DailyNotification::findOrFail($id);
        $notification->update(['is_active' => !$notification->is_active]);
        Cache::forget('today_notification_' . now()->format('Y-m-d'));
        $status = $notification->is_active ? 'تفعيل' : 'تعطيل';
        $this->dispatch('toast', ['type' => 'success', 'message' => "تم {$status} الإشعار بنجاح"]);
    }
    
    private function resetForm()
    {
        $this->notificationId = null;
        $this->title = '';
        $this->body = '';
        $this->type = 'khulq';
        $this->scheduledDate = '';
        $this->sendTime = '07:00';
        $this->isActive = true;
        $this->resetValidation();
    }
    
    public function render()
    {
        // Upcoming notifications
        $upcomingQuery = DailyNotification::query()
            ->where('is_sent', false)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('scheduled_date')
                      ->orWhere('scheduled_date', '>=', now()->format('Y-m-d'));
            });
        
        if ($this->typeFilter) {
            $upcomingQuery->where('type', $this->typeFilter);
        }
        
        $upcoming = $upcomingQuery->orderBy('scheduled_date')->paginate(10, ['*'], 'upcomingPage');
        
        // Sent history (from notification_logs)
        $sentQuery = NotificationLog::with('notification')
            ->whereNotNull('sent_at')
            ->latest('sent_at');
        
        $sentHistory = $sentQuery->paginate(10, ['*'], 'sentPage');
        
        $types = [
            'khulq' => 'خلق',
            'nafl' => 'نافلة',
            'dua' => 'دعاء',
            'reminder' => 'تذكير',
        ];
        
        return view('livewire.admin.notifications.notification-manager', [
            'upcoming' => $upcoming,
            'sentHistory' => $sentHistory,
            'types' => $types,
        ])->layout('layouts.admin', ['page_title' => 'الإشعارات اليومية']);
    }
}
