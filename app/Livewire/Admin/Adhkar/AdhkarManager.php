<?php

namespace App\Livewire\Admin\Adhkar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdhkarCategory;
use App\Models\Dhikr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdhkarManager extends Component
{
    use WithPagination;

    public $activeTab = 'categories'; // 'categories' or 'adhkar'
    
    // Category Form
    public $categoryId = null;
    public $categoryName = '';
    public $categoryIcon = '';
    public $categoryOrder = 0;
    public $categoryIsActive = true;
    
    // Dhikr Form
    public $dhikrId = null;
    public $dhikrText = '';
    public $dhikrCategoryId = '';
    public $dhikrSource = '';
    public $dhikrCount = 1;
    public $dhikrOrder = 0;
    public $dhikrIsActive = true;
    
    // Filters
    public $categoryFilter = '';
    public $search = '';
    
    // Modals
    public $showCategoryModal = false;
    public $showDhikrModal = false;
    public $showDeleteModal = false;
    public $deleteType = ''; // 'category' or 'dhikr'
    public $deleteId = null;
    
    protected $queryString = ['activeTab', 'categoryFilter', 'search'];
    
    // Validation rules for categories
    protected function categoryRules()
    {
        return [
            'categoryName' => 'required|string|max:100',
            'categoryIcon' => 'nullable|string|max:10',
            'categoryOrder' => 'nullable|integer|min:0',
            'categoryIsActive' => 'boolean',
        ];
    }
    
    // Validation rules for dhikr
    protected function dhikrRules()
    {
        return [
            'dhikrText' => 'required|string|min:5|max:1000',
            'dhikrCategoryId' => 'required|exists:adhkar_categories,id',
            'dhikrSource' => 'nullable|string|max:255',
            'dhikrCount' => 'required|integer|min:1',
            'dhikrOrder' => 'nullable|integer|min:0',
            'dhikrIsActive' => 'boolean',
        ];
    }
    
    protected function messages()
    {
        return [
            'categoryName.required' => 'اسم التصنيف مطلوب',
            'categoryName.max' => 'اسم التصنيف يجب ألا يتجاوز 100 حرف',
            'dhikrText.required' => 'نص الذكر مطلوب',
            'dhikrText.min' => 'نص الذكر يجب أن يكون 5 أحرف على الأقل',
            'dhikrText.max' => 'نص الذكر يجب ألا يتجاوز 1000 حرف',
            'dhikrCategoryId.required' => 'التصنيف مطلوب',
            'dhikrCategoryId.exists' => 'التصنيف غير موجود',
            'dhikrCount.min' => 'عدد التكرار يجب أن يكون على الأقل 1',
        ];
    }
    
    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
    
    // Category Methods
    public function openCategoryModal()
    {
        $this->resetCategoryForm();
        $this->showCategoryModal = true;
    }
    
    public function editCategory($id)
    {
        $category = AdhkarCategory::findOrFail($id);
        $this->categoryId = $category->id;
        $this->categoryName = $category->name;
        $this->categoryIcon = $category->icon;
        $this->categoryOrder = $category->order;
        $this->categoryIsActive = $category->is_active;
        $this->showCategoryModal = true;
    }
    
    public function saveCategory()
    {
        $validated = $this->validate($this->categoryRules(), $this->messages());
        
        $data = [
            'name' => $this->categoryName,
            'icon' => $this->categoryIcon,
            'order' => $this->categoryOrder,
            'is_active' => $this->categoryIsActive,
        ];
        
        if (!$this->categoryId) {
            $data['slug'] = \Illuminate\Support\Str::slug($this->categoryName);
        }
        
        if ($this->categoryId) {
            $category = AdhkarCategory::findOrFail($this->categoryId);
            $category->update($data);
            $toastMessage = 'تم تحديث التصنيف بنجاح';
        } else {
            AdhkarCategory::create($data);
            $toastMessage = 'تم إضافة التصنيف بنجاح';
        }
        
        $this->showCategoryModal = false;
        $this->resetCategoryForm();
        
        // Clear adhkar cache to reflect changes in API
        Cache::forget('adhkar_categories');
        
        $this->dispatch('toast', ['type' => 'success', 'message' => $toastMessage]);
    }
    
    // Dhikr Methods
    public function openDhikrModal()
    {
        $this->resetDhikrForm();
        $this->showDhikrModal = true;
    }
    
    public function editDhikr($id)
    {
        $dhikr = Dhikr::findOrFail($id);
        $this->dhikrId = $dhikr->id;
        $this->dhikrText = $dhikr->text;
        $this->dhikrCategoryId = $dhikr->category_id;
        $this->dhikrSource = $dhikr->source;
        $this->dhikrCount = $dhikr->count;
        $this->dhikrOrder = $dhikr->order;
        $this->dhikrIsActive = $dhikr->is_active;
        $this->showDhikrModal = true;
    }
    
    public function saveDhikr()
    {
        $validated = $this->validate($this->dhikrRules(), $this->messages());
        
        $data = [
            'text' => $this->dhikrText,
            'category_id' => $this->dhikrCategoryId,
            'source' => $this->dhikrSource,
            'count' => $this->dhikrCount,
            'order' => $this->dhikrOrder,
            'is_active' => $this->dhikrIsActive,
        ];
        
        if ($this->dhikrId) {
            $dhikr = Dhikr::findOrFail($this->dhikrId);
            $dhikr->update($data);
            $toastMessage = 'تم تحديث الذكر بنجاح';
        } else {
            Dhikr::create($data);
            $toastMessage = 'تم إضافة الذكر بنجاح';
        }
        
        $this->showDhikrModal = false;
        $this->resetDhikrForm();
        
        // Clear adhkar caches to reflect changes in API
        Cache::forget('adhkar_categories');
        if ($this->dhikrCategoryId) {
            $category = AdhkarCategory::find($this->dhikrCategoryId);
            if ($category) {
                Cache::forget("adhkar_category_{$category->slug}");
            }
        }
        
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
        if ($this->deleteType === 'category') {
            $category = AdhkarCategory::findOrFail($this->deleteId);
            $slug = $category->slug;
            $category->delete();
            Cache::forget('adhkar_categories');
            Cache::forget("adhkar_category_{$slug}");
            $message = 'تم حذف التصنيف بنجاح';
        } else {
            $dhikr = Dhikr::findOrFail($this->deleteId);
            $category = $dhikr->category;
            $dhikr->delete();
            Cache::forget('adhkar_categories');
            if ($category) {
                Cache::forget("adhkar_category_{$category->slug}");
            }
            $message = 'تم حذف الذكر بنجاح';
        }
        
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteType = '';
        $this->dispatch('toast', ['type' => 'success', 'message' => $message]);
    }
    
    // Toggle Methods
    public function toggleCategoryActive($id)
    {
        $category = AdhkarCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        Cache::forget('adhkar_categories');
        Cache::forget("adhkar_category_{$category->slug}");
        $status = $category->is_active ? 'تفعيل' : 'تعطيل';
        $this->dispatch('toast', ['type' => 'success', 'message' => "تم {$status} التصنيف بنجاح"]);
    }
    
    public function toggleDhikrActive($id)
    {
        $dhikr = Dhikr::findOrFail($id);
        $dhikr->update(['is_active' => !$dhikr->is_active]);
        Cache::forget('adhkar_categories');
        if ($dhikr->category) {
            Cache::forget("adhkar_category_{$dhikr->category->slug}");
        }
        $status = $dhikr->is_active ? 'تفعيل' : 'تعطيل';
        $this->dispatch('toast', ['type' => 'success', 'message' => "تم {$status} الذكر بنجاح"]);
    }
    
    // Order Methods
    public function moveCategoryOrder($id, $direction)
    {
        $category = AdhkarCategory::findOrFail($id);
        $newOrder = $direction === 'up' ? $category->order - 1 : $category->order + 1;
        
        if ($newOrder >= 0) {
            $category->update(['order' => $newOrder]);
            Cache::forget('adhkar_categories');
            $this->dispatch('toast', ['type' => 'success', 'message' => 'تم تحديث الترتيب بنجاح']);
        }
    }
    
    // Reset Forms
    private function resetCategoryForm()
    {
        $this->categoryId = null;
        $this->categoryName = '';
        $this->categoryIcon = '';
        $this->categoryOrder = 0;
        $this->categoryIsActive = true;
        $this->resetValidation();
    }
    
    private function resetDhikrForm()
    {
        $this->dhikrId = null;
        $this->dhikrText = '';
        $this->dhikrCategoryId = '';
        $this->dhikrSource = '';
        $this->dhikrCount = 1;
        $this->dhikrOrder = 0;
        $this->dhikrIsActive = true;
        $this->resetValidation();
    }
    
    public function render()
    {
        $categories = AdhkarCategory::withCount('adhkar')
            ->orderBy('order')
            ->paginate(10, ['*'], 'categoriesPage');
        
        $adhkarQuery = Dhikr::with('category');
        
        if ($this->categoryFilter) {
            $adhkarQuery->where('category_id', $this->categoryFilter);
        }
        
        if ($this->search) {
            $adhkarQuery->where('text', 'like', '%' . $this->search . '%');
        }
        
        $adhkar = $adhkarQuery->latest()->paginate(10, ['*'], 'adhkarPage');
        
        $categoryOptions = AdhkarCategory::where('is_active', true)
            ->orderBy('order')
            ->pluck('name', 'id');
        
        return view('livewire.admin.adhkar.adhkar-manager', [
            'categories' => $categories,
            'adhkar' => $adhkar,
            'categoryOptions' => $categoryOptions,
        ])->layout('layouts.admin', ['page_title' => 'الأذكار']);
    }
}
