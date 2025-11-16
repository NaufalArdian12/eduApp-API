<?php

namespace App\Repositories;

use App\Models\VideoProgress;

class VideoProgressRepository
{
    public function upsert(array $keys, array $values): VideoProgress
    {
        return VideoProgress::updateOrCreate($keys, $values);
    }
}
