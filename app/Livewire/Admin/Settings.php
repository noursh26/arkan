<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\MotivationalMessage;
use App\Models\Dhikr;
use App\Models\IslamicRuling;
use App\Models\AppDevice;
use App\Models\DailyNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Settings extends Component
{
    // Account Settings
    public $name = '';
    public $email = '';
    public $currentPassword = '';
    public $newPassword = '';
    public $newPasswordConfirmation = '';
    
    // Notification Settings
    public $defaultNotificationTime = '07:00';
    
    // Stats
    public $apiVersion = 'v1';
    
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'currentPassword' => 'nullable|string',
            'newPassword' => 'nullable|string|min:8|confirmed',
        ];
    }
    
    protected function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صالح',
            'newPassword.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'newPassword.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ];
    }
    
    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }
    
    public function updateAccount()
    {
        $user = Auth::user();
        
        // Check if email is unique
        if ($this->email !== $user->email) {
            $existingUser = User::where('email', $this->email)->where('id', '!=', $user->id)->first();
            if ($existingUser) {
                $this->addError('email', 'البريد الإلكتروني مستخدم مسبقاً');
                return;
            }
        }
        
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ], $this->messages());
        
        $user->update($validated);
        
        $this->dispatch('toast', ['type' => 'success', 'message' => 'تم تحديث بيانات الحساب بنجاح']);
    }
    
    public function updatePassword()
    {
        $validated = $this->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
        ], [
            'currentPassword.required' => 'كلمة المرور الحالية مطلوبة',
            'newPassword.required' => 'كلمة المرور الجديدة مطلوبة',
            'newPassword.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'newPassword.confirmed' => 'تأكيد كلمة المرور غير متطابق',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'كلمة المرور الحالية غير صحيحة');
            return;
        }
        
        $user->update(['password' => Hash::make($this->newPassword)]);
        
        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        
        $this->dispatch('toast', ['type' => 'success', 'message' => 'تم تغيير كلمة المرور بنجاح']);
    }
    
    public function updateNotificationSettings()
    {
        // Save to settings/config
        // For now, this is just a placeholder
        $this->dispatch('toast', ['type' => 'success', 'message' => 'تم حفظ إعدادات الإشعارات بنجاح']);
    }
    
    public function render()
    {
        // Stats
        $stats = [
            'messages' => MotivationalMessage::count(),
            'adhkar' => Dhikr::count(),
            'rulings' => IslamicRuling::count(),
            'devices' => AppDevice::count(),
            'notifications' => DailyNotification::count(),
        ];
        
        return view('livewire.admin.settings', [
            'stats' => $stats,
        ])->layout('layouts.admin', ['page_title' => 'الإعدادات']);
    }
}
