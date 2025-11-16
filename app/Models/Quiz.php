<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'title',
        'prompt',
        'canonical_answer',
        'acceptable_answers',
        'numeric_tolerance',
        'eval_type',
        'rubric_id',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'acceptable_answers' => 'array',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }
}
