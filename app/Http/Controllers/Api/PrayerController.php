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
