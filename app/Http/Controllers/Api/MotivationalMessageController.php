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
