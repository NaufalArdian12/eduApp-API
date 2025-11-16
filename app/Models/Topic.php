<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level_id',
        'title',
        'description',
        'order_index',
        'min_videos_before_assessment',
        'is_assessment_enabled',
    ];

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
}

