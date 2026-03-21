<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dhikr extends Model
{
    use SoftDeletes;

    protected $table = 'adhkar';
    protected $fillable = ['category_id', 'text', 'source', 'count', 'order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AdhkarCategory::class, 'category_id');
    }
}
