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
