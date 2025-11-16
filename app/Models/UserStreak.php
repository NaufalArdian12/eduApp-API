<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $user_id
 * @property int $current_streak_days
 * @property int $longest_streak_days
 * @property Carbon|null $last_active_date
 */
class UserStreak extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'current_streak_days',
        'longest_streak_days',
        'last_active_date',
    ];

    // pakai datetime biar jelas Carbon
    protected $casts = [
        'last_active_date' => 'datetime:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
