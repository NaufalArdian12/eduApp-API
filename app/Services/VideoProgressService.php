<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\VideoProgressRepository;
use App\Services\GamificationService;
use Illuminate\Support\Carbon;

class VideoProgressService
{
    public function __construct(
        private VideoProgressRepository $videoProgress,
        private GamificationService $gamification,
    ) {
    }

    public function updateProgress(
        User $user,
        int $videoId,
        int $secondsWatched,
        ?bool $isCompleted
    ) {
        $progress = $this->videoProgress->upsert(
            [
                'user_id' => $user->id,
                'video_id' => $videoId,
            ],
            [
                'seconds_watched' => $secondsWatched,
                'is_completed' => $isCompleted ?? false,
                'last_watched_at' => Carbon::now(),
            ]
        );

        if ($isCompleted && !$progress->wasRecentlyCreated && !$progress->getOriginal('is_completed')) {
            $this->gamification->rewardActivity(
                $user,
                'VIDEO_COMPLETED',
                'video',
                $videoId,
                3
            );
        }

        return $progress;
    }
}
