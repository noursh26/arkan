<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdhkarCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'icon', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function adhkar(): HasMany
    {
        return $this->hasMany(Dhikr::class, 'category_id');
    }

    public function activeAdhkar(): HasMany
    {
        return $this->hasMany(Dhikr::class, 'category_id')->where('is_active', true)->orderBy('order');
    }
}
