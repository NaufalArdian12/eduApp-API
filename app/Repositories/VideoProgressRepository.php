<?php

namespace App\Repositories;

use App\Models\VideoProgress;

class VideoProgressRepository
{
    public function upsert(array $keys, array $values): VideoProgress
    {
        return VideoProgress::updateOrCreate($keys, $values);
    }

    public function findForUserAndVideo(int $userId, int $videoId): ?VideoProgress
    {
        return VideoProgress::where('user_id', $userId)
            ->where('video_id', $videoId)
            ->first();
    }

}
