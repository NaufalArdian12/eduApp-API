<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class UserStatsController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $user->load(['streak', 'points']);

        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'streak' => $user->streak,
            'points' => $user->points,
        ];

        return ApiResponse::ok($data);
    }

    public function activityLogs(Request $request)
    {
        $user = $request->user();

        $logs = $user->activityLogs()
            ->orderByDesc('occurred_at')
            ->limit(50)
            ->get();

        return ApiResponse::ok($logs);
    }
}
