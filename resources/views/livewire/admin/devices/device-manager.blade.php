<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">إجمالي الأجهزة</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">أندرويد</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['android'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">iOS</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['ios'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">نشط خلال 7 أيام</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['active_7_days'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-4">
        <select wire:model.live="platformFilter" class="px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 bg-white">
            <option value="">كل المنصات</option>
            <option value="android">أندرويد</option>
            <option value="ios">iOS</option>
        </select>
        
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث بمعرف الجهاز..." 
               class="px-4 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
    </div>

    <!-- Devices Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">معرف الجهاز</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">المنصة</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">الإصدار</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">آخر نشاط</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">تاريخ التسجيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($devices as $device)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <code class="text-sm text-gray-800 bg-gray-100 px-2 py-1 rounded">{{ Str::limit($device->device_id, 25) }}</code>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $device->platform === 'android' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $device->platform === 'android' ? 'أندرويد' : 'iOS' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $device->app_version ?? '-' }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">
                                @if($device->last_seen_at)
                                    <span class="{{ $device->last_seen_at->diffInDays() < 7 ? 'text-green-600' : 'text-gray-500' }}">
                                        {{ $device->last_seen_at->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $device->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500 text-lg">لا توجد أجهزة مسجلة</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($devices->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $devices->links() }}
            </div>
        @endif
    </div>
</div>
