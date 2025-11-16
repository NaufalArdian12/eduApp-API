<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'title',
        'youtube_id',
        'youtube_url',
        'duration_seconds',
        'order_index',
        'is_active',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }
}
