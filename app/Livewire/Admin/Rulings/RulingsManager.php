<?php

namespace App\Livewire\Admin\Rulings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RulingTopic;
use App\Models\IslamicRuling;
use Illuminate\Support\Facades\Cache;

class RulingsManager extends Component
{
    use WithPagination;

    public $activeTab = 'topics'; // 'topics' or 'rulings'
    
    // Topic Form
    public $topicId = null;
    public $topicName = '';
    public $topicIcon = '';
    public $topicOrder = 0;
    public $topicIsActive = true;
    
    // Ruling Form
    public $rulingId = null;
    public $rulingTopicId = '';
    public $rulingQuestion = '';
    public $rulingAnswer = '';
    public $rulingEvidence = '';
    public $rulingIsActive = true;
    
    // Filters
    public $topicFilter = '';
    public $search = '';
    
    // Modals
    public $showTopicModal = false;
    public $showRulingModal = false;
    public $showDeleteModal = false;
    public $deleteType = ''; // 'topic' or 'ruling'
    public $deleteId = null;
    
    protected $queryString = ['activeTab', 'topicFilter', 'search'];
    
    protected function topicRules()
    {
        return [
            'topicName' => 'required|string|max:100',
            'topicIcon' => 'nullable|string|max:10',
            'topicOrder' => 'nullable|integer|min:0',
            'topicIsActive' => 'boolean',
        ];
    }
    
    protected function rulingRules()
    {
        return [
            'rulingTopicId' => 'required|exists:ruling_topics,id',
            'rulingQuestion' => 'required|string|min:10|max:500',
            'rulingAnswer' => 'required|string|min:20',
            'rulingEvidence' => 'nullable|string',
            'rulingIsActive' => 'boolean',
        ];
    }
    
    protected function messages()
    {
        return [
            'topicName.required' => 'اسم الموضوع مطلوب',
            'topicName.max' => 'اسم الموضوع يجب ألا يتجاوز 100 حرف',
            'rulingTopicId.required' => 'الموضوع مطلوب',
            'rulingTopicId.exists' => 'الموضوع غير موجود',
            'rulingQuestion.required' => 'السؤال مطلوب',
            'rulingQuestion.min' => 'السؤال يجب أن يكون 10 أحرف على الأقل',
            'rulingQuestion.max' => 'السؤال يجب ألا يتجاوز 500 حرف',
            'rulingAnswer.required' => 'الجواب مطلوب',
            'rulingAnswer.min' => 'الجواب يجب أن يكون 20 حرف على الأقل',
        ];
    }
    
    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
    
    // Topic Methods
    public function openTopicModal()
    {
        $this->resetTopicForm();
        $this->showTopicModal = true;
    }
    
    public function editTopic($id)
    {
        $topic = RulingTopic::findOrFail($id);
        $this->topicId = $topic->id;
        $this->topicName = $topic->name;
        $this->topicIcon = $topic->icon;
        $this->topicOrder = $topic->order;
        $this->topicIsActive = $topic->is_active;
        $this->showTopicModal = true;
    }
    
    public function saveTopic()
    {
        $validated = $this->validate($this->topicRules(), $this->messages());
        
        $data = [
            'name' => $this->topicName,
            'icon' => $this->topicIcon,
            'order' => $this->topicOrder,
            'is_active' => $this->topicIsActive,
        ];
        
        if ($this->topicId) {
            $topic = RulingTopic::findOrFail($this->topicId);
            $topic->update($data);
            $toastMessage = 'تم تحديث الموضوع بنجاح';
        } else {
            RulingTopic::create($data);
            $toastMessage = 'تم إضافة الموضوع بنجاح';
        }
        
        $this->showTopicModal = false;
        $this->resetTopicForm();
        
        // Clear rulings cache to reflect changes in API
        Cache::forget('ruling_topics');
        
        $this->dispatch('toast', ['type' => 'success', 'message' => $toastMessage]);
    }
    
    // Ruling Methods
    public function openRulingModal()
    {
        $this->resetRulingForm();
        $this->showRulingModal = true;
    }
    
    public function editRuling($id)
    {
        $ruling = IslamicRuling::findOrFail($id);
        $this->rulingId = $ruling->id;
        $this->rulingTopicId = $ruling->topic_id;
        $this->rulingQuestion = $ruling->question;
        $this->rulingAnswer = $ruling->answer;
        $this->rulingEvidence = $ruling->evidence;
        $this->rulingIsActive = $ruling->is_active;
        $this->showRulingModal = true;
    }
    
    public function saveRuling()
    {
        $validated = $this->validate($this->rulingRules(), $this->messages());
        
        $data = [
            'topic_id' => $this->rulingTopicId,
            'question' => $this->rulingQuestion,
            'answer' => $this->rulingAnswer,
            'evidence' => $this->rulingEvidence,
            'is_active' => $this->rulingIsActive,
        ];
        
        if ($this->rulingId) {
            $ruling = IslamicRuling::findOrFail($this->rulingId);
            $ruling->update($data);
            $toastMessage = 'تم تحديث الحكم بنجاح';
        } else {
            IslamicRuling::create($data);
            $toastMessage = 'تم إضافة الحكم بنجاح';
        }
        
        $this->showRulingModal = false;
        $this->resetRulingForm();
        
        // Clear rulings cache to reflect changes in API
        Cache::forget('ruling_topics');
        
        $this->dispatch('toast', ['type' => 'success', 'message' => $toastMessage]);
    }
    
    // Delete Methods
    public function confirmDelete($type, $id)
    {
        $this->deleteType = $type;
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    
    public function delete()
    {
        if ($this->deleteType === 'topic') {
            RulingTopic::findOrFail($this->deleteId)->delete();
            Cache::forget('ruling_topics');
            $message = 'تم حذف الموضوع بنجاح';
        } else {
            IslamicRuling::findOrFail($this->deleteId)->delete();
            Cache::forget('ruling_topics');
            $message = 'تم حذف الحكم بنجاح';
        }
        
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteType = '';
        $this->dispatch('toast', ['type' => 'success', 'message' => $message]);
    }
    
    // Toggle Methods
    public function toggleTopicActive($id)
    {
        $topic = RulingTopic::findOrFail($id);
        $topic->update(['is_active' => !$topic->is_active]);
        Cache::forget('ruling_topics');
        $status = $topic->is_active ? 'تفعيل' : 'تعطيل';
        $this->dispatch('toast', ['type' => 'success', 'message' => "تم {$status} الموضوع بنجاح"]);
    }
    
    public function toggleRulingActive($id)
    {
        $ruling = IslamicRuling::findOrFail($id);
        $ruling->update(['is_active' => !$ruling->is_active]);
        Cache::forget('ruling_topics');
        $status = $ruling->is_active ? 'تفعيل' : 'تعطيل';
        $this->dispatch('toast', ['type' => 'success', 'message' => "تم {$status} الحكم بنجاح"]);
    }
    
    // Reset Forms
    private function resetTopicForm()
    {
        $this->topicId = null;
        $this->topicName = '';
        $this->topicIcon = '';
        $this->topicOrder = 0;
        $this->topicIsActive = true;
        $this->resetValidation();
    }
    
    private function resetRulingForm()
    {
        $this->rulingId = null;
        $this->rulingTopicId = '';
        $this->rulingQuestion = '';
        $this->rulingAnswer = '';
        $this->rulingEvidence = '';
        $this->rulingIsActive = true;
        $this->resetValidation();
    }
    
    public function render()
    {
        $topics = RulingTopic::withCount('rulings')
            ->orderBy('order')
            ->paginate(10, ['*'], 'topicsPage');
        
        $rulingsQuery = IslamicRuling::with('topic');
        
        if ($this->topicFilter) {
            $rulingsQuery->where('topic_id', $this->topicFilter);
        }
        
        if ($this->search) {
            $rulingsQuery->where('question', 'like', '%' . $this->search . '%');
        }
        
        $rulings = $rulingsQuery->latest()->paginate(10, ['*'], 'rulingsPage');
        
        $topicOptions = RulingTopic::where('is_active', true)
            ->orderBy('order')
            ->pluck('name', 'id');
        
        return view('livewire.admin.rulings.rulings-manager', [
            'topics' => $topics,
            'rulings' => $rulings,
            'topicOptions' => $topicOptions,
        ])->layout('layouts.admin', ['page_title' => 'الأحكام الشرعية']);
    }
}
