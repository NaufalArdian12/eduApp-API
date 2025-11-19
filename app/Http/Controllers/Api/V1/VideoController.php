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
        $query = Video::query()
            ->with(['topic.gradeLevel.subject'])
            ->where('is_active', true)
            ->whereHas('topic', function ($q) {
                $q->whereHas('gradeLevel', function ($q2) {
                    $q2->where('is_active', true)
                        ->whereHas('subject', function ($q3) {
                            $q3->where('is_active', true);
                        });
                });
            })
            ->orderBy('topic_id')
            ->orderBy('order_index');

        if ($request->has('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        $videos = $query->get()->makeHidden([
            'created_at',
            'updated_at',
        ]);

        return ApiResponse::ok($videos);
    }

    public function show(Video $video)
    {
        $video->load(['topic.gradeLevel.subject']);

        if (
            ! $video->is_active ||
            ! $video->topic?->gradeLevel?->is_active ||
            ! $video->topic?->gradeLevel?->subject?->is_active
        ) {
            return ApiResponse::fail(
                'NOT_FOUND',
                'Video is not available.',
                404
            );
        }

        $video->makeHidden([
            'created_at',
            'updated_at',
        ]);

        return ApiResponse::ok($video);
    }
}
