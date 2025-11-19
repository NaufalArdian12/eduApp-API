<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVideoRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Models\Video;
use App\Services\AdminContentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct(
        private AdminContentService $service
    ) {
    }

    public function index(Request $request)
    {
        $topicId = $request->query('topic_id');

        $videos = $this->service->listVideos(
            $topicId ? (int) $topicId : null
        );

        return ApiResponse::ok($videos);
    }

    public function store(StoreVideoRequest $request)
    {
        $data = $request->validated();

        $video = $this->service->createVideo($data);

        return ApiResponse::ok($video, null, 201);
    }

    public function show(Video $video)
    {
        $video->load('topic');

        return ApiResponse::ok($video);
    }

    public function update(UpdateVideoRequest $request, Video $video)
    {
        $data = $request->validated();

        $video = $this->service->updateVideo($video, $data);

        return ApiResponse::ok($video);
    }

    public function destroy(Video $video)
    {
        $this->service->deleteVideo($video);

        return ApiResponse::ok(null, null, 204);
    }
}
