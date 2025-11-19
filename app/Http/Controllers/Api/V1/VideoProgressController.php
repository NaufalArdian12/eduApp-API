<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UpsertVideoProgressRequest;
use App\Services\VideoProgressService;
use App\Support\ApiResponse;

class VideoProgressController extends Controller
{
    public function __construct(
        private VideoProgressService $videoProgressService
    ) {
    }

    public function storeOrUpdate(UpsertVideoProgressRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $progress = $this->videoProgressService->updateProgress(
            $user,
            $data['video_id'],
            $data['seconds_watched'],
            $data['is_completed'] ?? false,
        );

        return ApiResponse::ok($progress);
    }

}
