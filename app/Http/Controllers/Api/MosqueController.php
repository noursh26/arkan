<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        $cacheKey = sprintf('mosques_%.6f_%.6f_%d', $lat, $lng, $radius);

        $data = Cache::remember($cacheKey, 600, function () use ($lat, $lng, $radius, $apiKey) {
            $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
                'location' => "{$lat},{$lng}",
                'radius'   => $radius,
                'type'     => 'mosque',
                'key'      => $apiKey,
                'language' => 'ar',
            ]);

        if ($response->failed()) {
                Log::warning('Google Places API failed', ['status' => $response->status()]);
                return $this->error('تعذر الاتصال بخدمة تحديد المواقع، يرجى المحاولة لاحقاً', 503);
            }

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
