<div>
    <!-- Tabs -->
    <div class="flex items-center gap-2 mb-6 border-b border-gray-200">
        <button wire:click="setTab('categories')" 
                class="px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'categories' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}">
            التصنيفات
        </button>
        <button wire:click="setTab('adhkar')" 
                class="px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'adhkar' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}">
            الأذكار
        </button>
    </div>

    @if($activeTab === 'categories')
        <!-- Categories Tab -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">تصنيفات الأذكار</h3>
            <button wire:click="openCategoryModal" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>إضافة تصنيف</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">الاسم</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الأيقونة</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الترتيب</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">عدد الأذكار</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الحالة</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $category->name }}</td>
                                <td class="px-4 py-3 text-center text-xl">{{ $category->icon ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <span class="text-sm text-gray-600">{{ $category->order }}</span>
                                        <div class="flex flex-col">
                                            <button wire:click="moveCategoryOrder({{ $category->id }}, 'up')" class="text-gray-400 hover:text-gray-600 leading-none">▲</button>
                                            <button wire:click="moveCategoryOrder({{ $category->id }}, 'down')" class="text-gray-400 hover:text-gray-600 leading-none">▼</button>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $category->adhkar_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleCategoryActive({{ $category->id }})" 
                                            class="px-3 py-1 rounded text-xs font-medium transition-colors {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="editCategory({{ $category->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete('category', {{ $category->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg">لا توجد تصنيفات</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($categories->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Adhkar Tab -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <select wire:model.live="categoryFilter" class="px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 bg-white">
                    <option value="">كل التصنيفات</option>
                    @foreach($categoryOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث في الأذكار..." 
                       class="px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
            </div>
            
            <button wire:click="openDhikrModal" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>إضافة ذكر</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">نص الذكر</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">التصنيف</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">المصدر</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">العدد</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الحالة</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($adhkar as $dhikr)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-800" title="{{ $dhikr->text }}">
                                        {{ Str::limit($dhikr->text, 50) }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $dhikr->category?->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $dhikr->source ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-primary/10 text-primary rounded text-xs font-medium">{{ $dhikr->count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleDhikrActive({{ $dhikr->id }})" 
                                            class="px-3 py-1 rounded text-xs font-medium transition-colors {{ $dhikr->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $dhikr->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="editDhikr({{ $dhikr->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete('dhikr', {{ $dhikr->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg">لا توجد أذكار</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($adhkar->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $adhkar->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- Category Modal -->
    @if($showCategoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 modal-backdrop" wire:click="$set('showCategoryModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">{{ $categoryId ? 'تعديل تصنيف' : 'إضافة تصنيف جديد' }}</h3>
                        <button wire:click="$set('showCategoryModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="saveCategory" class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم التصنيف <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="categoryName" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('categoryName') border-red-500 @enderror">
                            @error('categoryName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الأيقونة (emoji)</label>
                            <input type="text" wire:model="categoryIcon" placeholder="☀️" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                            <input type="number" wire:model="categoryOrder" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="categoryIsActive" id="categoryIsActive" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="categoryIsActive" class="text-sm text-gray-700">نشط</label>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" wire:click="$set('showCategoryModal', false)" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">{{ $categoryId ? 'تحديث' : 'حفظ' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Dhikr Modal -->
    @if($showDhikrModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 modal-backdrop" wire:click="$set('showDhikrModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">{{ $dhikrId ? 'تعديل ذكر' : 'إضافة ذكر جديد' }}</h3>
                        <button wire:click="$set('showDhikrModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="saveDhikr" class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">نص الذكر <span class="text-red-500">*</span></label>
                            <textarea wire:model="dhikrText" rows="4" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('dhikrText') border-red-500 @enderror"></textarea>
                            @error('dhikrText') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">التصنيف <span class="text-red-500">*</span></label>
                            <select wire:model="dhikrCategoryId" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('dhikrCategoryId') border-red-500 @enderror">
                                <option value="">اختر التصنيف</option>
                                @foreach($categoryOptions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('dhikrCategoryId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">المصدر</label>
                            <input type="text" wire:model="dhikrSource" placeholder="رواه البخاري" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">عدد التكرار</label>
                                <input type="number" wire:model="dhikrCount" min="1" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                                <input type="number" wire:model="dhikrOrder" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="dhikrIsActive" id="dhikrIsActive" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="dhikrIsActive" class="text-sm text-gray-700">نشط</label>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" wire:click="$set('showDhikrModal', false)" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">{{ $dhikrId ? 'تحديث' : 'حفظ' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 modal-backdrop" wire:click="$set('showDeleteModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">تأكيد الحذف</h3>
                        <p class="text-gray-600 mb-6">هل أنت متأكد من حذف هذا العنصر؟ لا يمكن التراجع عن هذا الإجراء.</p>
                        <div class="flex justify-center gap-3">
                            <button wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg">إلغاء</button>
                            <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">حذف</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
