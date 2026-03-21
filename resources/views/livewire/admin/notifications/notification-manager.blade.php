<div>
    <!-- Tabs -->
    <div class="flex items-center gap-2 mb-6 border-b border-gray-200">
        <button wire:click="setTab('upcoming')" 
                class="px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'upcoming' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}">
            الجدولة
        </button>
        <button wire:click="setTab('sent')" 
                class="px-4 py-2 text-sm font-medium transition-colors {{ $activeTab === 'sent' ? 'text-primary border-b-2 border-primary' : 'text-gray-500 hover:text-gray-700' }}">
            السجل
        </button>
    </div>

    @if($activeTab === 'upcoming')
        <!-- Upcoming Notifications -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3">
                <select wire:model.live="typeFilter" class="px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 bg-white">
                    <option value="">كل الأنواع</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <button wire:click="openModal" class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>إضافة إشعار</span>
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">العنوان</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">النص</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">النوع</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">التاريخ</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">وقت الإرسال</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الحالة</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($upcoming as $notification)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $notification->title }}</td>
                                <td class="px-4 py-3">
                                    <p class="text-sm text-gray-600" title="{{ $notification->body }}">
                                        {{ Str::limit($notification->body, 40) }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-medium 
                                        {{ $notification->type === 'khulq' ? 'bg-green-100 text-green-700' : 
                                           ($notification->type === 'nafl' ? 'bg-blue-100 text-blue-700' : 
                                           ($notification->type === 'dua' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700')) }}">
                                        {{ $notification->type_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">
                                    {{ $notification->scheduled_date?->format('Y-m-d') ?? 'متكرر' }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $notification->send_time }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button wire:click="toggleActive({{ $notification->id }})" 
                                            class="px-3 py-1 rounded text-xs font-medium transition-colors {{ $notification->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $notification->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="confirmSendNow({{ $notification->id }})" class="p-1.5 text-green-600 hover:bg-green-50 rounded" title="إرسال الآن">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                        </button>
                                        <button wire:click="edit({{ $notification->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $notification->id }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg">لا توجد إشعارات قادمة</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($upcoming->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $upcoming->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Sent History -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">العنوان</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">تاريخ الإرسال</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الأجهزة</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">ناجح</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">فشل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sentHistory as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $log->notification?->title ?? 'غير معروف' }}</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $log->sent_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $log->devices_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">{{ $log->success_count }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 {{ $log->failure_count > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500' }} rounded text-xs">{{ $log->failure_count }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-gray-500 text-lg">لا يوجد سجل إشعارات</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($sentHistory->hasPages())
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $sentHistory->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 modal-backdrop" wire:click="$set('showModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800">{{ $notificationId ? 'تعديل إشعار' : 'إضافة إشعار جديد' }}</h3>
                        <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <form wire:submit="save" class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">العنوان <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="title" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('title') border-red-500 @enderror">
                            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">النص <span class="text-red-500">*</span></label>
                            <textarea wire:model="body" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('body') border-red-500 @enderror"></textarea>
                            @error('body') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">النوع <span class="text-red-500">*</span></label>
                            <select wire:model="type" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('type') border-red-500 @enderror">
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الإرسال (اختياري)</label>
                                <input type="date" wire:model="scheduledDate" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
                                <p class="text-xs text-gray-500 mt-1">اتركه فارغاً للإشعارات المتكررة</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">وقت الإرسال <span class="text-red-500">*</span></label>
                                <input type="time" wire:model="sendTime" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('sendTime') border-red-500 @enderror">
                                @error('sendTime') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" wire:model="isActive" id="isActive" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="isActive" class="text-sm text-gray-700">نشط</label>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg">إلغاء</button>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">{{ $notificationId ? 'تحديث' : 'حفظ' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Send Now Confirmation Modal -->
    @if($showSendModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 modal-backdrop" wire:click="$set('showSendModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-sm w-full p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">إرسال الإشعار</h3>
                        <p class="text-gray-600 mb-6">هل أنت متأكد من إرسال هذا الإشعار إلى جميع الأجهزة المسجلة الآن؟</p>
                        <div class="flex justify-center gap-3">
                            <button wire:click="$set('showSendModal', false)" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg">إلغاء</button>
                            <button wire:click="sendNow" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">إرسال الآن</button>
                        </div>
                    </div>
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
                        <p class="text-gray-600 mb-6">هل أنت متأكد من حذف هذا الإشعار؟ لا يمكن التراجع عن هذا الإجراء.</p>
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
