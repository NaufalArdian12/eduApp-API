<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'youtube_id' => ['required', 'string', 'max:255'],
            'youtube_url' => ['required', 'url'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $video = $this->service->createVideo($data);

        return ApiResponse::ok($video, null, 201);
    }

    public function show(Video $video)
    {
        $video->load('topic');

        return ApiResponse::ok($video);
    }

    public function update(Request $request, Video $video)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'youtube_id' => ['sometimes', 'string', 'max:255'],
            'youtube_url' => ['sometimes', 'url'],
            'duration_seconds' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'order_index' => ['sometimes', 'nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $video = $this->service->updateVideo($video, $data);

        return ApiResponse::ok($video);
    }

    public function destroy(Video $video)
    {
        $this->service->deleteVideo($video);

        return ApiResponse::ok(null, null, 204);
    }
}
