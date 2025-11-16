<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_no',
        'status',
        'answer',
        'label',
        'ai_score_percent',
        'ai_feedback',
        'ai_model',
        'ai_raw',
        'manual_label',
        'manual_note',
        'reviewed_by',
        'is_overridden',
    ];

    protected $casts = [
        'ai_feedback' => 'array',
        'ai_raw'      => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
