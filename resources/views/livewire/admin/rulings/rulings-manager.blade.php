<div>
    <!-- Tabs -->
    <div class="flex items-center gap-2 mb-6 border-b border-gray-200">
        <button wire:click="setTab('topics')" 
                class="px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'topics' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}">
            المواضيع
        </button>
        <button wire:click="setTab('rulings')" 
                class="px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'rulings' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}">
            الأحكام
        </button>
    </div>

    @if($activeTab === 'topics')
        <!-- Topics Tab -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">مواضيع الأحكام الشرعية</h3>
            <button wire:click="openTopicModal" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>إضافة موضوع</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">الاسم</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الأيقونة</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">عدد الأحكام</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الحالة</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topics as $topic)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $topic->name }}</td>
                                <td class="px-4 py-3 text-center text-xl">{{ $topic->icon ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $topic->rulings_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleTopicActive({{ $topic->id }})" 
                                            class="px-3 py-1 rounded text-xs font-medium transition-colors {{ $topic->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $topic->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="editTopic({{ $topic->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete('topic', {{ $topic->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg">لا توجد مواضيع</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($topics->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $topics->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Rulings Tab -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <select wire:model.live="topicFilter" class="px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 bg-white">
                    <option value="">كل المواضيع</option>
                    @foreach($topicOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث في الأحكام..." 
                       class="px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
            </div>
            
            <button wire:click="openRulingModal" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>إضافة حكم</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">السؤال</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">الموضوع</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الحالة</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rulings as $ruling)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-800" title="{{ $ruling->question }}">
                                        {{ Str::limit($ruling->question, 60) }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $ruling->topic?->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleRulingActive({{ $ruling->id }})" 
                                            class="px-3 py-1 rounded text-xs font-medium transition-colors {{ $ruling->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $ruling->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="editRuling({{ $ruling->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete('ruling', {{ $ruling->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg">لا توجد أحكام</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($rulings->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $rulings->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- Topic Modal -->
    @if($showTopicModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 modal-backdrop" wire:click="$set('showTopicModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">{{ $topicId ? 'تعديل موضوع' : 'إضافة موضوع جديد' }}</h3>
                        <button wire:click="$set('showTopicModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="saveTopic" class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم الموضوع <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="topicName" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('topicName') border-red-500 @enderror">
                            @error('topicName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الأيقونة (emoji)</label>
                            <input type="text" wire:model="topicIcon" placeholder="💧" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                            <input type="number" wire:model="topicOrder" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="topicIsActive" id="topicIsActive" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="topicIsActive" class="text-sm text-gray-700">نشط</label>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" wire:click="$set('showTopicModal', false)" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">{{ $topicId ? 'تحديث' : 'حفظ' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Ruling Modal -->
    @if($showRulingModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 modal-backdrop" wire:click="$set('showRulingModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">{{ $rulingId ? 'تعديل حكم' : 'إضافة حكم جديد' }}</h3>
                        <button wire:click="$set('showRulingModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="saveRuling" class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الموضوع <span class="text-red-500">*</span></label>
                            <select wire:model="rulingTopicId" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('rulingTopicId') border-red-500 @enderror">
                                <option value="">اختر الموضوع</option>
                                @foreach($topicOptions as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('rulingTopicId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">السؤال <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="rulingQuestion" placeholder="ما حكم...؟" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('rulingQuestion') border-red-500 @enderror">
                            @error('rulingQuestion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الجواب <span class="text-red-500">*</span></label>
                            <textarea wire:model="rulingAnswer" rows="6" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('rulingAnswer') border-red-500 @enderror"></textarea>
                            @error('rulingAnswer') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الدليل الشرعي</label>
                            <textarea wire:model="rulingEvidence" rows="3" placeholder="قال رسول الله ﷺ..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20"></textarea>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="rulingIsActive" id="rulingIsActive" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="rulingIsActive" class="text-sm text-gray-700">نشط</label>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" wire:click="$set('showRulingModal', false)" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">{{ $rulingId ? 'تحديث' : 'حفظ' }}</button>
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
