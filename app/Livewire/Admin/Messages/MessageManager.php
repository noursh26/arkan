<?php

namespace App\Livewire\Admin\Messages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MotivationalMessage;

class MessageManager extends Component
{
    use WithPagination;

    // Form fields
    public $messageId = null;
    public $text = '';
    public $prayer_time = 'any';
    public $is_active = true;
    
    // Filter
    public $filterPrayerTime = '';
    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;
    
    protected $queryString = ['filterPrayerTime', 'search'];
    
    protected function rules()
    {
        return [
            'text' => 'required|string|min:10|max:300',
            'prayer_time' => 'required|in:any,fajr,dhuhr,asr,maghrib,isha',
            'is_active' => 'boolean',
        ];
    }
    
    protected function messages()
    {
        return [
            'text.required' => 'نص الرسالة مطلوب',
            'text.min' => 'نص الرسالة يجب أن يكون 10 أحرف على الأقل',
            'text.max' => 'نص الرسالة يجب ألا يتجاوز 300 حرف',
            'prayer_time.required' => 'وقت الصلاة مطلوب',
            'prayer_time.in' => 'وقت الصلاة غير صالح',
        ];
    }
    
    public function updatingFilterPrayerTime()
    {
        $this->resetPage();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }
    
    public function edit($id)
    {
        $message = MotivationalMessage::findOrFail($id);
        $this->messageId = $message->id;
        $this->text = $message->text;
        $this->prayer_time = $message->prayer_time;
        $this->is_active = $message->is_active;
        $this->showModal = true;
    }
    
    public function save()
    {
        $validated = $this->validate();
        
        if ($this->messageId) {
            $message = MotivationalMessage::findOrFail($this->messageId);
            $message->update($validated);
            $toastMessage = 'تم تحديث الرسالة بنجاح';
        } else {
            MotivationalMessage::create($validated);
            $toastMessage = 'تم إضافة الرسالة بنجاح';
        }
        
        $this->showModal = false;
        $this->resetForm();
        
        $this->dispatch('toast', ['type' => 'success', 'message' => $toastMessage]);
    }
    
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    
    public function delete()
    {
        if ($this->deleteId) {
            MotivationalMessage::findOrFail($this->deleteId)->delete();
            $this->showDeleteModal = false;
            $this->deleteId = null;
            $this->dispatch('toast', ['type' => 'success', 'message' => 'تم حذف الرسالة بنجاح']);
        }
    }
    
    public function toggleActive($id)
    {
        $message = MotivationalMessage::findOrFail($id);
        $message->update(['is_active' => !$message->is_active]);
        $status = $message->is_active ? 'تفعيل' : 'تعطيل';
        $this->dispatch('toast', ['type' => 'success', 'message' => "تم {$status} الرسالة بنجاح"]);
    }
    
    private function resetForm()
    {
        $this->messageId = null;
        $this->text = '';
        $this->prayer_time = 'any';
        $this->is_active = true;
        $this->resetValidation();
    }
    
    public function render()
    {
        $query = MotivationalMessage::query();
        
        if ($this->filterPrayerTime) {
            $query->where('prayer_time', $this->filterPrayerTime);
        }
        
        if ($this->search) {
            $query->where('text', 'like', '%' . $this->search . '%');
        }
        
        $messages = $query->latest()->paginate(10);
        
        $prayerTimes = [
            'any' => 'الكل',
            'fajr' => 'الفجر',
            'dhuhr' => 'الظهر',
            'asr' => 'العصر',
            'maghrib' => 'المغرب',
            'isha' => 'العشاء',
        ];
        
        return view('livewire.admin.messages.message-manager', [
            'messages' => $messages,
            'prayerTimes' => $prayerTimes,
        ])->layout('layouts.admin', ['page_title' => 'رسائل الأذان']);
    }
}
