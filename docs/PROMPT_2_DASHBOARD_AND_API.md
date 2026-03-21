# Arkani Admin — Windsurf Prompt 2: Admin Dashboard + REST API

> **Context:** Prompt 1 is complete. All models, migrations, and seeders are done. Now build the full admin UI with Livewire 3 + Tailwind CSS, and all REST API endpoints. Read `docs/ADMIN_FEATURES.md` and `docs/API_SPEC.md` before starting.

---

## PART A — Admin Layout & Routes

### Step A1 — Admin Routes (`routes/web.php`)

Replace the entire `routes/web.php` with:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Messages\MessagesList;
use App\Livewire\Admin\Adhkar\AdhkarList;
use App\Livewire\Admin\Rulings\RulingsList;
use App\Livewire\Admin\Notifications\NotificationsList;
use App\Livewire\Admin\Devices\DevicesList;
use App\Livewire\Admin\Settings\AdminSettings;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                 Dashboard::class)->name('dashboard');
    Route::get('/messages',         MessagesList::class)->name('messages');
    Route::get('/adhkar',           AdhkarList::class)->name('adhkar');
    Route::get('/rulings',          RulingsList::class)->name('rulings');
    Route::get('/notifications',    NotificationsList::class)->name('notifications');
    Route::get('/devices',          DevicesList::class)->name('devices');
    Route::get('/settings',         AdminSettings::class)->name('settings');
});

require __DIR__.'/auth.php';
```

Generate auth routes if not exists:
```bash
php artisan breeze:install blade
```
Or manually ensure `/login` route exists.

---

### Step A2 — Admin Layout (`resources/views/layouts/admin.blade.php`)

Create the full RTL sidebar layout:

```html
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'أركاني — لوحة التحكم' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 font-arabic antialiased">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <div class="w-64 bg-gray-900 flex flex-col flex-shrink-0">
            <!-- Logo -->
            <div class="flex items-center gap-3 p-6 border-b border-gray-700">
                <div class="w-9 h-9 bg-primary-600 rounded-xl flex items-center justify-center text-white text-lg">🕌</div>
                <div>
                    <div class="text-white font-semibold text-sm">أركاني</div>
                    <div class="text-gray-400 text-xs">لوحة التحكم</div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    لوحة التحكم
                </a>

                <!-- Content Section -->
                <div class="pt-4 pb-1">
                    <p class="text-xs font-medium text-gray-500 px-3 mb-1">المحتوى</p>
                </div>

                <a href="{{ route('admin.messages') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.messages') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    رسائل الأذان
                </a>

                <a href="{{ route('admin.adhkar') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.adhkar') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    الأذكار
                </a>

                <a href="{{ route('admin.rulings') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.rulings') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    الأحكام الشرعية
                </a>

                <!-- Notifications Section -->
                <div class="pt-4 pb-1">
                    <p class="text-xs font-medium text-gray-500 px-3 mb-1">الإشعارات</p>
                </div>

                <a href="{{ route('admin.notifications') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.notifications') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    الإشعارات اليومية
                </a>

                <!-- Users Section -->
                <div class="pt-4 pb-1">
                    <p class="text-xs font-medium text-gray-500 px-3 mb-1">المستخدمون</p>
                </div>

                <a href="{{ route('admin.devices') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors {{ request()->routeIs('admin.devices') ? 'bg-primary-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    الأجهزة المسجلة
                </a>
            </nav>

            <!-- User + Logout -->
            <div class="p-3 border-t border-gray-700">
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg">
                    <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-gray-500 text-xs truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-300 transition-colors" title="تسجيل الخروج">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'لوحة التحكم' }}</h1>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">{{ now()->translatedFormat('l، j F Y') }}</span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div
        x-data="{ show: false, message: '', type: 'success' }"
        x-on:toast.window="show = true; message = $event.detail.message; type = $event.detail.type ?? 'success'; setTimeout(() => show = false, 3500)"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-4 left-4 z-50"
        style="display: none;"
    >
        <div :class="type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-amber-50 border-amber-200 text-amber-800'"
             class="flex items-center gap-3 px-4 py-3 rounded-xl border shadow-lg text-sm font-medium min-w-64">
            <span x-text="type === 'success' ? '✅' : type === 'error' ? '❌' : '⚠️'"></span>
            <span x-text="message"></span>
        </div>
    </div>

    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
```

---

## PART B — Livewire Components

For each Livewire component, create both the PHP class and Blade view. Every component uses `layout('layouts.admin')`.

---

### B1 — Dashboard (`app/Livewire/Admin/Dashboard.php`)

```php
<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\AppDevice;
use App\Models\NotificationLog;
use App\Models\Dhikr;
use App\Models\IslamicRuling;
use App\Models\DailyNotification;
use App\Models\MotivationalMessage;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'devices'          => AppDevice::count(),
            'notifications_week' => NotificationLog::where('sent_at', '>=', now()->subWeek())->count(),
            'adhkar'           => Dhikr::where('is_active', true)->count(),
            'rulings'          => IslamicRuling::where('is_active', true)->count(),
            'messages'         => MotivationalMessage::where('is_active', true)->count(),
        ];

        $todayNotification = DailyNotification::whereDate('scheduled_date', today())
            ->orWhereNull('scheduled_date')
            ->where('is_active', true)
            ->where('is_sent', false)
            ->first();

        $recentDevices = AppDevice::latest()->take(10)->get();
        $recentLogs    = NotificationLog::with('notification')->latest()->take(5)->get();

        return view('livewire.admin.dashboard', compact('stats', 'todayNotification', 'recentDevices', 'recentLogs'))
            ->title('لوحة التحكم');
    }
}
```

View `resources/views/livewire/admin/dashboard.blade.php`:
```html
<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card p-5">
            <p class="text-xs text-gray-500 mb-1">الأجهزة المسجلة</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['devices']) }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-500 mb-1">إشعارات هذا الأسبوع</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['notifications_week'] }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-500 mb-1">الأذكار النشطة</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['adhkar'] }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-500 mb-1">الأحكام الشرعية</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['rulings'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's notification status -->
        <div class="card p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">إشعار اليوم</h3>
            @if($todayNotification)
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm">
                    <p class="font-medium text-green-800">{{ $todayNotification->title }}</p>
                    <p class="text-green-700 text-xs mt-1">{{ Str::limit($todayNotification->body, 80) }}</p>
                    <span class="inline-block mt-2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $todayNotification->type_label }}</span>
                </div>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-700">
                    لم يُجدول إشعار لليوم
                </div>
            @endif
            <a href="{{ route('admin.notifications') }}" class="mt-3 inline-block text-xs text-primary-600 hover:text-primary-800">← إدارة الإشعارات</a>
        </div>

        <!-- Quick actions -->
        <div class="card p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">إجراءات سريعة</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.messages') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-600 py-2 border-b border-gray-100">
                    <span>💬</span> إضافة رسالة جديدة
                </a>
                <a href="{{ route('admin.rulings') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-600 py-2 border-b border-gray-100">
                    <span>📖</span> إضافة حكم شرعي
                </a>
                <a href="{{ route('admin.notifications') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-primary-600 py-2">
                    <span>📬</span> جدولة إشعار
                </a>
            </div>
        </div>

        <!-- Recent devices -->
        <div class="card p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">آخر الأجهزة المسجلة</h3>
            @forelse($recentDevices as $device)
                <div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
                    <span class="text-xs text-gray-500 font-mono">{{ Str::limit($device->device_id, 14) }}</span>
                    <span class="text-xs {{ $device->platform === 'android' ? 'text-green-600' : 'text-blue-600' }}">
                        {{ $device->platform === 'android' ? '🤖' : '🍎' }} {{ ucfirst($device->platform) }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-gray-400">لا أجهزة مسجلة بعد</p>
            @endforelse
        </div>
    </div>
</div>
```

---

### B2 — Messages (`app/Livewire/Admin/Messages/MessagesList.php`)

```php
<?php
namespace App\Livewire\Admin\Messages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MotivationalMessage;

class MessagesList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterPrayer = '';
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

    // Form fields
    public string $text = '';
    public string $prayer_time = 'any';
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'text'        => 'required|min:10|max:300',
            'prayer_time' => 'required|in:any,fajr,dhuhr,asr,maghrib,isha',
            'is_active'   => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilterPrayer(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['text', 'prayer_time', 'is_active', 'editingId']);
        $this->prayer_time = 'any';
        $this->is_active = true;
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $msg = MotivationalMessage::findOrFail($id);
        $this->editingId  = $msg->id;
        $this->text       = $msg->text;
        $this->prayer_time = $msg->prayer_time;
        $this->is_active  = $msg->is_active;
        $this->showForm   = true;
    }

    public function save(): void
    {
        $this->validate();
        if ($this->editingId) {
            MotivationalMessage::findOrFail($this->editingId)->update([
                'text' => $this->text, 'prayer_time' => $this->prayer_time, 'is_active' => $this->is_active,
            ]);
            $this->dispatch('toast', message: 'تم تعديل الرسالة بنجاح ✅', type: 'success');
        } else {
            MotivationalMessage::create([
                'text' => $this->text, 'prayer_time' => $this->prayer_time, 'is_active' => $this->is_active,
            ]);
            $this->dispatch('toast', message: 'تم إضافة الرسالة بنجاح ✅', type: 'success');
        }
        $this->showForm = false;
        $this->reset(['text', 'prayer_time', 'editingId']);
    }

    public function toggleActive(int $id): void
    {
        $msg = MotivationalMessage::findOrFail($id);
        $msg->update(['is_active' => !$msg->is_active]);
        $this->dispatch('toast', message: $msg->is_active ? 'تم تفعيل الرسالة' : 'تم تعطيل الرسالة', type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        MotivationalMessage::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'تم حذف الرسالة', type: 'success');
    }

    public function render()
    {
        $messages = MotivationalMessage::query()
            ->when($this->search, fn($q) => $q->where('text', 'like', "%{$this->search}%"))
            ->when($this->filterPrayer, fn($q) => $q->where('prayer_time', $this->filterPrayer))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.messages.messages-list', ['messages' => $messages])
            ->title('رسائل الأذان');
    }
}
```

View `resources/views/livewire/admin/messages/messages-list.blade.php` — create a full table with:
- Search input + prayer filter select at top
- "إضافة رسالة" button
- Table with columns: النص | وقت الصلاة | الحالة | الإجراءات
- Each row has Edit / Toggle / Delete buttons
- Modal/inline form for create/edit with validation error display
- Delete confirmation modal
- Empty state when no results
- Pagination

The form modal should be a fixed overlay div with backdrop, containing:
- Textarea for text (with character counter showing remaining of 300)
- Select for prayer_time with Arabic labels
- Toggle switch for is_active
- Save / Cancel buttons

---

### B3 — Adhkar (`app/Livewire/Admin/Adhkar/AdhkarList.php`)

```php
<?php
namespace App\Livewire\Admin\Adhkar;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdhkarCategory;
use App\Models\Dhikr;

class AdhkarList extends Component
{
    use WithPagination;

    public string $activeTab = 'adhkar'; // 'categories' | 'adhkar'
    public string $search = '';
    public string $filterCategory = '';
    public bool $showForm = false;
    public bool $showCategoryForm = false;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public string $deleteType = 'dhikr'; // 'dhikr' | 'category'

    // Dhikr form
    public ?int $editingId = null;
    public string $text = '';
    public ?int $category_id = null;
    public string $source = '';
    public int $count = 1;
    public int $order = 0;
    public bool $is_active = true;

    // Category form
    public ?int $editingCategoryId = null;
    public string $cat_name = '';
    public string $cat_slug = '';
    public string $cat_icon = '';
    public int $cat_order = 0;
    public bool $cat_is_active = true;

    protected function rules(): array
    {
        return [
            'text'        => 'required|min:5',
            'category_id' => 'required|exists:adhkar_categories,id',
            'source'      => 'nullable|string|max:255',
            'count'       => 'required|integer|min:1|max:1000',
            'order'       => 'integer|min:0',
            'is_active'   => 'boolean',
        ];
    }

    protected function categoryRules(): array
    {
        return [
            'cat_name'      => 'required|string|max:100',
            'cat_slug'      => ['required','string','max:100', $this->editingCategoryId ? "unique:adhkar_categories,slug,{$this->editingCategoryId}" : 'unique:adhkar_categories,slug'],
            'cat_icon'      => 'nullable|string|max:50',
            'cat_order'     => 'integer|min:0',
            'cat_is_active' => 'boolean',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['text', 'category_id', 'source', 'count', 'order', 'is_active', 'editingId']);
        $this->count = 1;
        $this->is_active = true;
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $dhikr = Dhikr::findOrFail($id);
        $this->editingId   = $dhikr->id;
        $this->text        = $dhikr->text;
        $this->category_id = $dhikr->category_id;
        $this->source      = $dhikr->source ?? '';
        $this->count       = $dhikr->count;
        $this->order       = $dhikr->order;
        $this->is_active   = $dhikr->is_active;
        $this->showForm    = true;
    }

    public function saveDhikr(): void
    {
        $this->validate();
        $data = ['text' => $this->text, 'category_id' => $this->category_id, 'source' => $this->source ?: null, 'count' => $this->count, 'order' => $this->order, 'is_active' => $this->is_active];

        if ($this->editingId) {
            Dhikr::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'تم تعديل الذكر بنجاح', type: 'success');
        } else {
            Dhikr::create($data);
            $this->dispatch('toast', message: 'تم إضافة الذكر بنجاح', type: 'success');
        }
        $this->showForm = false;
    }

    public function openCreateCategory(): void
    {
        $this->reset(['cat_name', 'cat_slug', 'cat_icon', 'cat_order', 'cat_is_active', 'editingCategoryId']);
        $this->cat_is_active = true;
        $this->showCategoryForm = true;
    }

    public function openEditCategory(int $id): void
    {
        $cat = AdhkarCategory::findOrFail($id);
        $this->editingCategoryId = $cat->id;
        $this->cat_name      = $cat->name;
        $this->cat_slug      = $cat->slug;
        $this->cat_icon      = $cat->icon ?? '';
        $this->cat_order     = $cat->order;
        $this->cat_is_active = $cat->is_active;
        $this->showCategoryForm = true;
    }

    public function saveCategory(): void
    {
        $this->validate($this->categoryRules());
        $data = ['name' => $this->cat_name, 'slug' => $this->cat_slug, 'icon' => $this->cat_icon ?: null, 'order' => $this->cat_order, 'is_active' => $this->cat_is_active];

        if ($this->editingCategoryId) {
            AdhkarCategory::findOrFail($this->editingCategoryId)->update($data);
            $this->dispatch('toast', message: 'تم تعديل التصنيف', type: 'success');
        } else {
            AdhkarCategory::create($data);
            $this->dispatch('toast', message: 'تم إضافة التصنيف', type: 'success');
        }
        $this->showCategoryForm = false;
    }

    public function confirmDelete(int $id, string $type = 'dhikr'): void
    {
        $this->deleteId   = $id;
        $this->deleteType = $type;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleteType === 'category') {
            AdhkarCategory::findOrFail($this->deleteId)->delete();
        } else {
            Dhikr::findOrFail($this->deleteId)->delete();
        }
        $this->showDeleteModal = false;
        $this->dispatch('toast', message: 'تم الحذف بنجاح', type: 'success');
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $categories = AdhkarCategory::withCount('adhkar')->orderBy('order')->get();

        $adhkar = Dhikr::with('category')
            ->when($this->search, fn($q) => $q->where('text', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->orderBy('category_id')->orderBy('order')
            ->paginate(20);

        return view('livewire.admin.adhkar.adhkar-list', compact('categories', 'adhkar'))
            ->title('الأذكار');
    }
}
```

Create the full Blade view for this component at `resources/views/livewire/admin/adhkar/adhkar-list.blade.php` with:
- Two tab buttons: "التصنيفات" and "الأذكار"
- Categories tab: table with name/icon/count/status + edit/delete actions + "إضافة تصنيف" button
- Adhkar tab: table with truncated text/category/source/count/status + actions + "إضافة ذكر" button
- Search and category filter above adhkar table
- Both forms as modals
- Delete confirmation modal

---

### B4 — Rulings (`app/Livewire/Admin/Rulings/RulingsList.php`)

Build following same pattern as AdhkarList but for IslamicRuling and RulingTopic models.

Fields for Ruling form:
- topic_id (select from topics)
- question (text input, max 500)
- answer (textarea, large)
- evidence (textarea, optional)
- is_active (toggle)

Fields for Topic form:
- name, icon, order, is_active

Two tabs: "المواضيع" and "الأحكام"
Include search by question text + filter by topic.

---

### B5 — Notifications (`app/Livewire/Admin/Notifications/NotificationsList.php`)

```php
<?php
namespace App\Livewire\Admin\Notifications;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DailyNotification;
use App\Models\NotificationLog;
use App\Models\AppDevice;
use App\Services\FcmService;

class NotificationsList extends Component
{
    use WithPagination;

    public string $activeTab = 'upcoming';
    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public bool $showSendModal = false;
    public ?int $deleteId = null;
    public ?int $sendId = null;
    public ?int $editingId = null;

    // Form fields
    public string $title = '';
    public string $body = '';
    public string $type = 'reminder';
    public string $scheduled_date = '';
    public string $send_time = '07:00';
    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'body'           => 'required|string|max:500',
            'type'           => 'required|in:khulq,nafl,dua,reminder',
            'scheduled_date' => 'nullable|date',
            'send_time'      => 'required',
            'is_active'      => 'boolean',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['title', 'body', 'type', 'scheduled_date', 'send_time', 'is_active', 'editingId']);
        $this->type = 'reminder';
        $this->send_time = '07:00';
        $this->is_active = true;
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $n = DailyNotification::findOrFail($id);
        $this->editingId       = $n->id;
        $this->title           = $n->title;
        $this->body            = $n->body;
        $this->type            = $n->type;
        $this->scheduled_date  = $n->scheduled_date?->format('Y-m-d') ?? '';
        $this->send_time       = substr($n->send_time, 0, 5);
        $this->is_active       = $n->is_active;
        $this->showForm        = true;
    }

    public function save(): void
    {
        $this->validate();
        $data = [
            'title'          => $this->title,
            'body'           => $this->body,
            'type'           => $this->type,
            'scheduled_date' => $this->scheduled_date ?: null,
            'send_time'      => $this->send_time . ':00',
            'is_active'      => $this->is_active,
        ];

        if ($this->editingId) {
            DailyNotification::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'تم تعديل الإشعار بنجاح', type: 'success');
        } else {
            DailyNotification::create($data);
            $this->dispatch('toast', message: 'تم إضافة الإشعار بنجاح', type: 'success');
        }
        $this->showForm = false;
    }

    public function confirmSend(int $id): void
    {
        $this->sendId = $id;
        $this->showSendModal = true;
    }

    public function sendNow(): void
    {
        $notification = DailyNotification::findOrFail($this->sendId);
        $fcm = app(FcmService::class);
        $result = $fcm->sendToAll($notification->title, $notification->body, ['type' => $notification->type]);

        $devicesCount = AppDevice::count();
        NotificationLog::create([
            'notification_id' => $notification->id,
            'devices_count'   => $devicesCount,
            'success_count'   => $result['success_count'] ?? 0,
            'failure_count'   => $result['failure_count'] ?? 0,
            'sent_at'         => now(),
        ]);

        $notification->update(['is_sent' => true, 'sent_at' => now()]);
        $this->showSendModal = false;
        $this->dispatch('toast', message: "تم إرسال الإشعار إلى {$devicesCount} جهاز ✅", type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        DailyNotification::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->dispatch('toast', message: 'تم حذف الإشعار', type: 'success');
    }

    public function render()
    {
        $upcoming = DailyNotification::where('is_sent', false)
            ->where('is_active', true)
            ->orderBy('scheduled_date')
            ->paginate(10, pageName: 'upcomingPage');

        $sentLogs = NotificationLog::with('notification')
            ->latest('sent_at')
            ->paginate(10, pageName: 'logsPage');

        return view('livewire.admin.notifications.notifications-list', compact('upcoming', 'sentLogs'))
            ->title('الإشعارات اليومية');
    }
}
```

Build full Blade view with:
- Tab: "المجدولة" (upcoming table with title/type/date/time/actions including "إرسال الآن")
- Tab: "السجل" (sent logs table with title/date/devices_count/success_count/failure_count)
- Form modal for creating/editing notifications
- Send confirmation modal ("هل تريد إرسال هذا الإشعار الآن لجميع الأجهزة؟")
- Delete confirmation modal

---

### B6 — Devices (`app/Livewire/Admin/Devices/DevicesList.php`)

```php
<?php
namespace App\Livewire\Admin\Devices;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AppDevice;

class DevicesList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterPlatform = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $stats = [
            'total'   => AppDevice::count(),
            'android' => AppDevice::where('platform', 'android')->count(),
            'ios'     => AppDevice::where('platform', 'ios')->count(),
            'active'  => AppDevice::where('last_seen_at', '>=', now()->subDays(7))->count(),
        ];

        $devices = AppDevice::query()
            ->when($this->search, fn($q) => $q->where('device_id', 'like', "%{$this->search}%"))
            ->when($this->filterPlatform, fn($q) => $q->where('platform', $this->filterPlatform))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.devices.devices-list', compact('stats', 'devices'))
            ->title('الأجهزة المسجلة');
    }
}
```

Build Blade view with stats cards at top + read-only searchable table.

---

## PART C — REST API

### C1 — API Routes (`routes/api.php`)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\PrayerController;
use App\Http\Controllers\Api\AdhkarController;
use App\Http\Controllers\Api\MotivationalMessageController;
use App\Http\Controllers\Api\IslamicRulingController;
use App\Http\Controllers\Api\DailyNotificationController;
use App\Http\Controllers\Api\MosqueController;

Route::prefix('v1')->group(function () {
    Route::post('devices/register',       [DeviceController::class, 'register']);
    Route::get('prayers/times',           [PrayerController::class, 'times']);
    Route::get('adhkar/categories',       [AdhkarController::class, 'categories']);
    Route::get('adhkar/{slug}',           [AdhkarController::class, 'byCategory']);
    Route::get('messages/random',         [MotivationalMessageController::class, 'random']);
    Route::get('rulings/topics',          [IslamicRulingController::class, 'topics']);
    Route::get('rulings',                 [IslamicRulingController::class, 'index']);
    Route::get('notifications/today',     [DailyNotificationController::class, 'today']);
    Route::get('mosques/nearby',          [MosqueController::class, 'nearby']);
});
```

### C2 — API Base Trait

Create `app/Http/Controllers/Api/ApiController.php`:

```php
<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected function success(mixed $data = null, string $message = null, int $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data, 'message' => $message], $status);
    }

    protected function error(string $message, int $status = 400): JsonResponse
    {
        return response()->json(['success' => false, 'data' => null, 'message' => $message], $status);
    }
}
```

### C3 — All API Controllers

Create each controller in `app/Http/Controllers/Api/`:

**DeviceController.php:**
```php
<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AppDevice;

class DeviceController extends ApiController
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'device_id'   => 'required|string|max:255',
            'fcm_token'   => 'required|string',
            'platform'    => 'required|in:android,ios',
            'app_version' => 'nullable|string|max:20',
        ]);

        AppDevice::updateOrCreate(
            ['device_id' => $data['device_id']],
            array_merge($data, ['last_seen_at' => now()])
        );

        return $this->success(['registered' => true]);
    }
}
```

**PrayerController.php:**
```php
<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\PrayerTimesService;

class PrayerController extends ApiController
{
    public function times(Request $request, PrayerTimesService $service)
    {
        $request->validate([
            'lat'    => 'required|numeric|between:-90,90',
            'lng'    => 'required|numeric|between:-180,180',
            'date'   => 'nullable|date_format:Y-m-d',
            'method' => 'nullable|integer|between:0,23',
        ]);

        $result = $service->getTimes(
            (float) $request->lat,
            (float) $request->lng,
            $request->date,
            (int) ($request->method ?? 4)
        );

        if (empty($result)) {
            return $this->error('تعذّر جلب أوقات الصلاة، يرجى المحاولة لاحقاً', 500);
        }

        return $this->success($result);
    }
}
```

**AdhkarController.php:**
```php
<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use App\Models\AdhkarCategory;

class AdhkarController extends ApiController
{
    public function categories()
    {
        $data = Cache::remember('adhkar_categories', 3600, function () {
            return AdhkarCategory::withCount(['adhkar' => fn($q) => $q->where('is_active', true)])
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
                ->map(fn($c) => [
                    'id'           => $c->id,
                    'name'         => $c->name,
                    'slug'         => $c->slug,
                    'icon'         => $c->icon,
                    'adhkar_count' => $c->adhkar_count,
                ]);
        });

        return $this->success($data);
    }

    public function byCategory(string $slug)
    {
        $category = AdhkarCategory::where('slug', $slug)->where('is_active', true)->first();

        if (!$category) {
            return $this->error('التصنيف غير موجود', 404);
        }

        $cacheKey = "adhkar_category_{$slug}";
        $data = Cache::remember($cacheKey, 3600, function () use ($category) {
            return [
                'category' => ['id' => $category->id, 'name' => $category->name, 'icon' => $category->icon],
                'adhkar'   => $category->activeAdhkar->map(fn($d) => [
                    'id'     => $d->id,
                    'text'   => $d->text,
                    'source' => $d->source,
                    'count'  => $d->count,
                    'order'  => $d->order,
                ]),
            ];
        });

        return $this->success($data);
    }
}
```

**MotivationalMessageController.php:**
```php
<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\MotivationalMessage;

class MotivationalMessageController extends ApiController
{
    public function random(Request $request)
    {
        $prayer = $request->input('prayer', 'any');
        $validPrayers = ['any', 'fajr', 'dhuhr', 'asr', 'maghrib', 'isha'];

        if (!in_array($prayer, $validPrayers)) {
            $prayer = 'any';
        }

        $message = MotivationalMessage::where('is_active', true)
            ->where(fn($q) => $q->where('prayer_time', $prayer)->orWhere('prayer_time', 'any'))
            ->inRandomOrder()
            ->first();

        if (!$message) {
            return $this->error('لا توجد رسائل متاحة', 404);
        }

        return $this->success([
            'id'          => $message->id,
            'text'        => $message->text,
            'prayer_time' => $message->prayer_time,
        ]);
    }
}
```

**IslamicRulingController.php:**
```php
<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\RulingTopic;
use App\Models\IslamicRuling;

class IslamicRulingController extends ApiController
{
    public function topics()
    {
        $data = Cache::remember('ruling_topics', 3600, function () {
            return RulingTopic::withCount(['rulings' => fn($q) => $q->where('is_active', true)])
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
                ->map(fn($t) => ['id' => $t->id, 'name' => $t->name, 'icon' => $t->icon, 'rulings_count' => $t->rulings_count]);
        });

        return $this->success($data);
    }

    public function index(Request $request)
    {
        $query = IslamicRuling::with('topic:id,name')
            ->where('is_active', true)
            ->when($request->topic_id, fn($q) => $q->where('topic_id', $request->topic_id))
            ->when($request->search, fn($q) => $q->where('question', 'like', "%{$request->search}%"));

        $paginated = $query->paginate(10);

        return $this->success([
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'per_page'     => $paginated->perPage(),
            'total'        => $paginated->total(),
            'items'        => $paginated->map(fn($r) => [
                'id'       => $r->id,
                'topic'    => $r->topic ? ['id' => $r->topic->id, 'name' => $r->topic->name] : null,
                'question' => $r->question,
                'answer'   => $r->answer,
                'evidence' => $r->evidence,
            ]),
        ]);
    }
}
```

**DailyNotificationController.php:**
```php
<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Cache;
use App\Models\DailyNotification;

class DailyNotificationController extends ApiController
{
    public function today()
    {
        $data = Cache::remember('today_notification_' . today()->format('Y-m-d'), now()->secondsUntilEndOfDay(), function () {
            $notif = DailyNotification::where('is_active', true)
                ->where(fn($q) => $q->whereDate('scheduled_date', today())->orWhereNull('scheduled_date'))
                ->inRandomOrder()
                ->first();

            if (!$notif) return null;

            return ['id' => $notif->id, 'title' => $notif->title, 'body' => $notif->body, 'type' => $notif->type];
        });

        return $this->success($data);
    }
}
```

**MosqueController.php:**
```php
<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MosqueController extends ApiController
{
    public function nearby(Request $request)
    {
        $request->validate([
            'lat'    => 'required|numeric|between:-90,90',
            'lng'    => 'required|numeric|between:-180,180',
            'radius' => 'nullable|integer|between:100,10000',
        ]);

        $lat    = (float) $request->lat;
        $lng    = (float) $request->lng;
        $radius = (int) ($request->radius ?? 2000);
        $apiKey = config('services.google_places.key');

        $cacheKey = "mosques_{$lat}_{$lng}_{$radius}";

        $data = Cache::remember($cacheKey, 600, function () use ($lat, $lng, $radius, $apiKey) {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
                'location' => "{$lat},{$lng}",
                'radius'   => $radius,
                'type'     => 'mosque',
                'key'      => $apiKey,
                'language' => 'ar',
            ]);

            if ($response->failed()) return [];

            $results = $response->json('results', []);

            return collect($results)->map(fn($place) => [
                'place_id'         => $place['place_id'],
                'name'             => $place['name'],
                'address'          => $place['vicinity'] ?? '',
                'latitude'         => $place['geometry']['location']['lat'],
                'longitude'        => $place['geometry']['location']['lng'],
                'rating'           => $place['rating'] ?? null,
                'distance_meters'  => $this->calculateDistance($lat, $lng, $place['geometry']['location']['lat'], $place['geometry']['location']['lng']),
            ])->sortBy('distance_meters')->values()->take(10);
        });

        return $this->success($data);
    }

    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): int
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
        return (int) round($earthRadius * 2 * atan2(sqrt($a), sqrt(1-$a)));
    }
}
```

---

## PART D — Final Steps

### D1 — Rate Limiting (in `bootstrap/app.php` or `RouteServiceProvider`)

```php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->ip());
});

RateLimiter::for('device-register', function (Request $request) {
    return Limit::perMinute(10)->by($request->ip());
});
```

Apply in `routes/api.php`:
```php
Route::middleware('throttle:device-register')->group(function () {
    Route::post('devices/register', [DeviceController::class, 'register']);
});
```

### D2 — Final Build & Cache Clear

```bash
npm run build
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
php artisan route:list
```

### D3 — Verification Checklist

Run these checks and confirm ALL pass:

```bash
# 1. All admin routes exist
php artisan route:list | grep "admin"
# Expected: dashboard, messages, adhkar, rulings, notifications, devices, settings

# 2. All API routes exist  
php artisan route:list | grep "api/v1"
# Expected: devices/register, prayers/times, adhkar/categories, adhkar/{slug}, messages/random, rulings/topics, rulings, notifications/today, mosques/nearby

# 3. No syntax errors
php artisan about

# 4. Models load correctly
php artisan tinker --execute="App\Models\AdhkarCategory::with('adhkar')->first()->toArray();"

# 5. API responds correctly (if server running)
# curl http://localhost:8000/api/v1/adhkar/categories
# curl http://localhost:8000/api/v1/messages/random?prayer=fajr
# curl http://localhost:8000/api/v1/notifications/today
```

---

## ✅ DONE — Both Prompts Complete

The full system should now have:
- ✅ Working admin login at `/login`
- ✅ Full RTL Arabic admin dashboard at `/admin`  
- ✅ CRUD for: Messages, Adhkar (with categories), Rulings (with topics), Notifications, Devices view
- ✅ REST API at `/api/v1` with 9 endpoints
- ✅ FCM push notification sending from admin panel
- ✅ Prayer times proxy
- ✅ Mosque search proxy
- ✅ Real seed data — no empty states anywhere
