<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::with('topic')
            ->orderBy('topic_id')
            ->orderBy('order_index');

        if ($request->has('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        return ApiResponse::ok($query->get());
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

        $video = Video::create($data);

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

        $video->update($data);

        return ApiResponse::ok($video);
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return ApiResponse::ok(null, null, 204);
    }
}
