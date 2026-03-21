<div class="max-w-4xl mx-auto space-y-6">
    <!-- Account Settings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            الحساب
        </h3>
        
        <form wire:submit="updateAccount" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                <input type="text" wire:model="name" class="w-full md:w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('name') border-red-500 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                <input type="email" wire:model="email" class="w-full md:w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('email') border-red-500 @enderror">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div class="pt-2">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>

    <!-- Password Settings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            تغيير كلمة المرور
        </h3>
        
        <form wire:submit="updatePassword" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الحالية</label>
                <input type="password" wire:model="currentPassword" class="w-full md:w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('currentPassword') border-red-500 @enderror">
                @error('currentPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور الجديدة</label>
                <input type="password" wire:model="newPassword" class="w-full md:w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 @error('newPassword') border-red-500 @enderror">
                @error('newPassword') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور الجديدة</label>
                <input type="password" wire:model="newPasswordConfirmation" class="w-full md:w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
            </div>
            
            <div class="pt-2">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    تغيير كلمة المرور
                </button>
            </div>
        </form>
    </div>

    <!-- Notification Settings -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            الإشعارات
        </h3>
        
        <form wire:submit="updateNotificationSettings" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">وقت الإشعار اليومي الافتراضي</label>
                <input type="time" wire:model="defaultNotificationTime" class="w-full md:w-1/2 px-3 py-2 rounded-lg border border-gray-300 focus:border-primary focus:ring focus:ring-primary/20">
            </div>
            
            <div class="pt-2">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                    حفظ الإعدادات
                </button>
            </div>
        </form>
    </div>

    <!-- App Stats -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            معلومات التطبيق
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">إصدار API</p>
                <p class="text-lg font-semibold text-gray-800">{{ $apiVersion }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">الرسائل التحفيزية</p>
                <p class="text-lg font-semibold text-gray-800">{{ $stats['messages'] }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">الأذكار</p>
                <p class="text-lg font-semibold text-gray-800">{{ $stats['adhkar'] }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">الأحكام الشرعية</p>
                <p class="text-lg font-semibold text-gray-800">{{ $stats['rulings'] }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">الأجهزة المسجلة</p>
                <p class="text-lg font-semibold text-gray-800">{{ $stats['devices'] }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">الإشعارات</p>
                <p class="text-lg font-semibold text-gray-800">{{ $stats['notifications'] }}</p>
            </div>
        </div>
    </div>
</div>
