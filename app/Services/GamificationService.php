<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ActivityLogRepository;
use App\Repositories\UserPointsRepository;
use App\Repositories\UserStreakRepository;

class GamificationService
{
    public function __construct(
        private ActivityLogRepository $activityLogs,
        private UserPointsRepository $pointsRepo,
        private UserStreakRepository $streakRepo,
    ) {
    }

    public function rewardActivity(
        User $user,
        string $action,
        ?string $refType,
        ?int $refId,
        int $points
    ): void {
        // log
        $this->activityLogs->log(
            $user->id,
            $action,
            $refType,
            $refId,
            $points,
        );

        // update poin
        if ($points > 0) {
            $this->pointsRepo->addPoints($user->id, $points);
        }

        // update streak
        $this->streakRepo->touchActivity($user->id);
    }

}
