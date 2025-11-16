<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\VideoProgressService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class VideoProgressController extends Controller
{
    public function __construct(
        private VideoProgressService $videoProgressService
    ) {
    }

    public function storeOrUpdate(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'video_id' => ['required', 'integer', 'exists:videos,id'],
            'seconds_watched' => ['required', 'integer', 'min:0'],
            'is_completed' => ['nullable', 'boolean'],
        ]);

        $progress = $this->videoProgressService->updateProgress(
            $user,
            $data['video_id'],
            $data['seconds_watched'],
            $data['is_completed'] ?? null,
        );

        return ApiResponse::ok($progress);
    }
}
