<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use Illuminate\Support\Collection;

class ActivityLogRepository
{
    public function log(
        int $userId,
        string $action,
        ?string $refType,
        ?int $refId,
        int $points
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'ref_type' => $refType,
            'ref_id' => $refId,
            'points' => $points,
            'occurred_at' => now(),
        ]);
    }

    public function forUser(int $userId, int $limit = 200): Collection
    {
        return ActivityLog::where('user_id', $userId)
            ->orderByDesc('occurred_at')
            ->limit($limit)
            ->get();
    }
}
