<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\PrayerController;
use App\Http\Controllers\Api\AdhkarController;
use App\Http\Controllers\Api\MotivationalMessageController;
use App\Http\Controllers\Api\IslamicRulingController;
use App\Http\Controllers\Api\DailyNotificationController;
use App\Http\Controllers\Api\MosqueController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Device Registration - 10 requests per minute (strict rate limiting)
Route::middleware('throttle:10,1')->post('devices/register', [DeviceController::class, 'register']);

// General API routes - 60 requests per minute
Route::middleware('throttle:60,1')->group(function () {
    Route::get('prayers/times', [PrayerController::class, 'times']);
    Route::get('adhkar/categories', [AdhkarController::class, 'categories']);
    Route::get('adhkar/{slug}', [AdhkarController::class, 'byCategory']);
    Route::get('messages/random', [MotivationalMessageController::class, 'random']);
    Route::get('rulings/topics', [IslamicRulingController::class, 'topics']);
    Route::get('rulings', [IslamicRulingController::class, 'index']);
    Route::get('notifications/today', [DailyNotificationController::class, 'today']);
    Route::get('mosques/nearby', [MosqueController::class, 'nearby']);
});
