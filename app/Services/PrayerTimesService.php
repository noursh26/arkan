<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PrayerTimesService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.aladhan.url', 'https://api.aladhan.com/v1');
    }

    public function getTimes(float $lat, float $lng, ?string $date = null, int $method = 4): array
    {
        $date = $date ?? now()->format('d-m-Y');
        $cacheKey = "prayer_times_{$lat}_{$lng}_{$date}_{$method}";

        return Cache::remember($cacheKey, 86400, function () use ($lat, $lng, $date, $method) {
            try {
                $response = Http::timeout(10)->get("{$this->baseUrl}/timings/{$date}", [
                    'latitude'  => $lat,
                    'longitude' => $lng,
                    'method'    => $method,
                ]);

                if ($response->failed()) {
                    Log::error('Aladhan API failed', [
                        'status'   => $response->status(),
                        'response' => $response->body(),
                    ]);
                    return [];
                }

                $data = $response->json('data');

                if (!$data) {
                    return [];
                }

                $timings = $data['timings'] ?? [];
                $filteredTimings = [
                    'Fajr'    => $this->cleanTime($timings['Fajr'] ?? ''),
                    'Dhuhr'   => $this->cleanTime($timings['Dhuhr'] ?? ''),
                    'Asr'     => $this->cleanTime($timings['Asr'] ?? ''),
                    'Maghrib' => $this->cleanTime($timings['Maghrib'] ?? ''),
                    'Isha'    => $this->cleanTime($timings['Isha'] ?? ''),
                ];

                return [
                    'date'     => $data['date']['readable'] ?? $date,
                    'timings'  => $filteredTimings,
                    'location' => [
                        'latitude'  => $lat,
                        'longitude' => $lng,
                        'timezone'  => $data['meta']['timezone'] ?? 'UTC',
                    ],
                ];
            } catch (\Exception $e) {
                Log::error('PrayerTimesService exception', [
                    'message' => $e->getMessage(),
                ]);
                return [];
            }
        });
    }

    private function cleanTime(string $time): string
    {
        return preg_replace('/\s*\(.*\)/', '', $time) ?? $time;
    }
}
