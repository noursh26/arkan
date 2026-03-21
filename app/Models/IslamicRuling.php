<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IslamicRuling extends Model
{
    use SoftDeletes;

    protected $fillable = ['topic_id', 'question', 'answer', 'evidence', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(RulingTopic::class, 'topic_id');
    }
}
