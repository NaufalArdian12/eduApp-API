<?php

namespace App\Repositories;

use App\Models\UserPoint;

class UserPointsRepository
{
    public function getOrCreate(int $userId): UserPoint
    {
        return UserPoint::firstOrCreate(
            ['user_id' => $userId],
            [
                'total_points' => 0,
                'weekly_points' => 0,
                'monthly_points' => 0,
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Tambah poin ke user (total + weekly + monthly).
     *
     * Dipakai di GamificationService::rewardActivity().
     *
     * @param  int  $userId
     * @param  int  $points
     * @return UserPoint
     */
    public function addPoints(int $userId, int $points): UserPoint
    {
        $userPoints = $this->getOrCreate($userId);

        $userPoints->total_points += $points;
        $userPoints->weekly_points += $points;
        $userPoints->monthly_points += $points;
        $userPoints->updated_at = now();
        $userPoints->save();

        return $userPoints;
    }
}
