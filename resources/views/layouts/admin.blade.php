<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') | أركاني</title>
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Livewire Styles -->
    @livewireStyles
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #0F6E56;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0a5240;
        }
        
        /* Toast Animation */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .toast-enter {
            animation: slideIn 0.3s ease-out forwards;
        }
        .toast-leave {
            animation: slideOut 0.3s ease-in forwards;
        }
        
        /* Sidebar Transition */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Primary Green Theme */
        .bg-primary { background-color: #0F6E56; }
        .bg-primary-dark { background-color: #0a5240; }
        .text-primary { color: #0F6E56; }
        .border-primary { border-color: #0F6E56; }
        .hover\:bg-primary-dark:hover { background-color: #0a5240; }
        
        /* Modal Backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased" x-data="{ sidebarOpen: false, toastNotifications: [] }" @toast.window="addToast($event.detail)">
    
    <!-- Toast Container -->
    <div class="fixed top-4 left-4 z-50 flex flex-col gap-2" id="toast-container">
        <template x-for="toast in toastNotifications" :key="toast.id">
            <div :class="`toast-enter flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg min-w-[300px] ${
                toast.type === 'success' ? 'bg-green-500 text-white' :
                toast.type === 'error' ? 'bg-red-500 text-white' :
                toast.type === 'warning' ? 'bg-amber-500 text-white' :
                'bg-blue-500 text-white'
            }`" x-transition:enter="toast-enter" x-transition:leave="toast-leave">
                <span x-text="toast.type === 'success' ? '✅' : toast.type === 'error' ? '❌' : toast.type === 'warning' ? '⚠️' : 'ℹ️'"></span>
                <span x-text="toast.message" class="font-medium"></span>
                <button @click="removeToast(toast.id)" class="mr-auto text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>
    
    <div class="min-h-screen flex">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden">
        </div>
        
        <!-- Sidebar -->
        <aside :class="`fixed lg:static inset-y-0 right-0 z-50 w-64 bg-gray-900 text-white transform sidebar-transition lg:transform-none ${sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'}`">
            <!-- Logo -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                        <span class="text-xl font-bold">أ</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">أركاني</h1>
                        <p class="text-xs text-gray-400">لوحة التحكم</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-4 px-3 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    <span class="font-medium">لوحة التحكم</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">المحتوى</p>
                </div>
                
                <!-- Messages -->
                <a href="{{ route('admin.messages') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.messages') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    <span class="font-medium">رسائل الأذان</span>
                </a>
                
                <!-- Adhkar -->
                <a href="{{ route('admin.adhkar') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.adhkar') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="font-medium">الأذكار</span>
                </a>
                
                <!-- Rulings -->
                <a href="{{ route('admin.rulings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.rulings') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                    <span class="font-medium">الأحكام الشرعية</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">الإشعارات</p>
                </div>
                
                <!-- Notifications -->
                <a href="{{ route('admin.notifications') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.notifications') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="font-medium">الإشعارات اليومية</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">المستخدمون</p>
                </div>
                
                <!-- Devices -->
                <a href="{{ route('admin.devices') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.devices') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <div class="relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span x-data="{ count: {{ \App\Models\AppDevice::count() }} }" 
                              x-init="
                                window.addEventListener('device-registered', (e) => { 
                                    count = e.detail.total_devices;
                                    window.dispatchToast('info', e.detail.message);
                                });
                                if (typeof Echo !== 'undefined') {
                                    Echo.channel('admin-notifications')
                                        .listen('.device.registered', (e) => {
                                            count = e.total_devices;
                                            window.dispatchToast('info', e.message);
                                        });
                                }
                              "
                              x-text="count" 
                              class="absolute -top-2 -left-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                        </span>
                    </div>
                    <span class="font-medium">الأجهزة المسجلة</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">النظام</p>
                </div>
                
                <!-- Settings -->
                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-primary text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-medium">الإعدادات</span>
                </a>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-red-900/30 hover:text-red-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="font-medium">تسجيل الخروج</span>
                    </button>
                </form>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 py-3 lg:px-6">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h2 class="text-xl font-bold text-gray-800">@yield('page_title', 'لوحة التحكم')</h2>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'المشرف' }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الإعدادات</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50">تسجيل الخروج</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    <!-- Toast JavaScript -->
    <script>
        function addToast(detail) {
            const toast = {
                id: Date.now(),
                type: detail.type || 'success',
                message: detail.message,
                timeout: detail.timeout || 5000
            };
            
            this.toastNotifications.push(toast);
            
            setTimeout(() => {
                this.removeToast(toast.id);
            }, toast.timeout);
        }
        
        function removeToast(id) {
            const index = this.toastNotifications.findIndex(t => t.id === id);
            if (index > -1) {
                this.toastNotifications.splice(index, 1);
            }
        }
        
        // Make functions available globally for Livewire
        document.addEventListener('DOMContentLoaded', function() {
            window.dispatchToast = function(type, message) {
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { type, message }
                }));
            };
            
            // Web Push Notification Setup
            if ('serviceWorker' in navigator && 'PushManager' in window) {
                registerServiceWorker();
            }
        });
        
        // Register Service Worker for Web Push
        async function registerServiceWorker() {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                console.log('Service Worker registered:', registration);
                
                // Check if already subscribed
                const subscription = await registration.pushManager.getSubscription();
                if (!subscription) {
                    // Subscribe to push notifications
                    subscribeToPush(registration);
                }
            } catch (error) {
                console.error('Service Worker registration failed:', error);
            }
        }
        
        // Subscribe to Web Push
        async function subscribeToPush(registration) {
            try {
                // Get VAPID key from server
                const response = await fetch('/webpush/vapid-key');
                const data = await response.json();
                
                const applicationServerKey = urlBase64ToUint8Array(data.publicKey);
                
                const subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey
                });
                
                // Send subscription to server
                await fetch('/webpush/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        endpoint: subscription.endpoint,
                        publicKey: arrayBufferToBase64(subscription.getKey('p256dh')),
                        authToken: arrayBufferToBase64(subscription.getKey('auth'))
                    })
                });
                
                console.log('Subscribed to push notifications');
            } catch (error) {
                console.error('Push subscription failed:', error);
            }
        }
        
        // Utility: Convert URL base64 to Uint8Array
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/\_/g, '/');
            
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
        
        // Utility: Convert ArrayBuffer to Base64
        function arrayBufferToBase64(buffer) {
            const bytes = new Uint8Array(buffer);
            let binary = '';
            for (let i = 0; i < bytes.byteLength; i++) {
                binary += String.fromCharCode(bytes[i]);
            }
            return window.btoa(binary);
        }
    </script>
    
    <!-- Laravel Echo & Reverb Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.0.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>
    <script>
        window.Pusher = Pusher;
        
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ config("broadcasting.connections.reverb.key") }}',
            cluster: 'mt1',
            wsHost: '{{ config("broadcasting.connections.reverb.options.host", "127.0.0.1") }}',
            wsPort: {{ config("broadcasting.connections.reverb.options.port", 8080) }},
            wssPort: {{ config("broadcasting.connections.reverb.options.port", 443) }},
            forceTLS: {{ config("broadcasting.connections.reverb.options.use_tls", false) ? 'true' : 'false' }},
            enabledTransports: ['ws', 'wss'],
            disableStats: true
        });
        
        // Listen for device registration events
        if (typeof Echo !== 'undefined') {
            Echo.channel('admin-notifications')
                .listen('.device.registered', (e) => {
                    window.dispatchEvent(new CustomEvent('device-registered', {
                        detail: {
                            total_devices: e.total_devices,
                            message: e.message,
                            device_id: e.device_id,
                            platform: e.platform
                        }
                    }));
                })
                .listen('.notification.sent', (e) => {
                    window.dispatchToast('success', e.message);
                });
        }
    </script>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    @stack('scripts')
</body>
</html>
