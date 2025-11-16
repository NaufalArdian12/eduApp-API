<?php

namespace App\Repositories;

use App\Models\UserStreak;
use Illuminate\Support\Carbon;

class UserStreakRepository
{

    public function getOrCreate(int $userId): UserStreak
    {
        return UserStreak::firstOrCreate(
            ['user_id' => $userId],
            [
                'current_streak_days' => 0,
                'longest_streak_days' => 0,
                'last_active_date' => null,
            ]
        );
    }


    public function touchActivity(int $userId): UserStreak
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $streak = $this->getOrCreate($userId);

        $last = $streak->last_active_date
            ? Carbon::parse($streak->last_active_date)
            : null;

        if ($last === null) {

            $streak->current_streak_days = 1;
        } elseif ($last->isSameDay($yesterday)) {

            $streak->current_streak_days += 1;
        } elseif ($last->isSameDay($today)) {

            return $streak;
        } else {

            $streak->current_streak_days = 1;
        }

        if ($streak->current_streak_days > $streak->longest_streak_days) {
            $streak->longest_streak_days = $streak->current_streak_days;
        }

        $streak->last_active_date = $today->toDateString();
        $streak->save();

        return $streak;
    }
}
