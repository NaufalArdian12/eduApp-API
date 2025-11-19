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
        $existing = $this->videoProgress->findForUserAndVideo($user->id, $videoId);

        $isCompletedFlag = $isCompleted ?? false;
        $alreadyCompleted = (bool) ($existing?->is_completed);

        $progress = $this->videoProgress->upsert(
            [
                'user_id' => $user->id,
                'video_id' => $videoId,
            ],
            [
                'seconds_watched' => max($existing?->seconds_watched ?? 0, $secondsWatched),
                'is_completed' => $alreadyCompleted || $isCompletedFlag,
                'last_watched_at' => Carbon::now(),
            ]
        );

        // Reward hanya ketika baru pertama kali completed
        if ($isCompletedFlag && !$alreadyCompleted) {
            $this->gamification->rewardActivity(
                $user,
                'VIDEO_COMPLETED',
                'video',
                $videoId,
                3,
            );
        }

        return $progress;
    }
}
