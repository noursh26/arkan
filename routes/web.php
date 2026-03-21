<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Messages\MessageManager;
use App\Livewire\Admin\Adhkar\AdhkarManager;
use App\Livewire\Admin\Rulings\RulingsManager;
use App\Livewire\Admin\Notifications\NotificationManager;
use App\Livewire\Admin\Devices\DeviceManager;
use App\Livewire\Admin\Settings;

use App\Http\Controllers\WebPushController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Web Push Notification Routes
Route::get('/webpush/vapid-key', [WebPushController::class, 'vapidKey'])->name('webpush.key');
Route::post('/webpush/subscribe', [WebPushController::class, 'subscribe'])->name('webpush.subscribe')->middleware('auth');
Route::post('/webpush/unsubscribe', [WebPushController::class, 'unsubscribe'])->name('webpush.unsubscribe')->middleware('auth');

// Home Route
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
});

// Logout route
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    
    // Messages Management
    Route::get('/messages', MessageManager::class)->name('messages');
    
    // Adhkar Management
    Route::get('/adhkar', AdhkarManager::class)->name('adhkar');
    
    // Rulings Management
    Route::get('/rulings', RulingsManager::class)->name('rulings');
    
    // Notifications Management
    Route::get('/notifications', NotificationManager::class)->name('notifications');
    
    // Devices Management
    Route::get('/devices', DeviceManager::class)->name('devices');
    
    // Settings
    Route::get('/settings', Settings::class)->name('settings');
    
});
