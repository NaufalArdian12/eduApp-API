<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubric extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'thresholds_json',
    ];

    protected $casts = [
        'thresholds_json' => 'array',
    ];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'rubric_id');
    }
}
