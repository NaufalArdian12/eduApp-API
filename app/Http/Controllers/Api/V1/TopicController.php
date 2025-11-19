<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::query()
            ->with(['gradeLevel.subject'])
            ->whereHas('gradeLevel', function ($q) {
                $q->where('is_active', true)
                  ->whereHas('subject', function ($q2) {
                      $q2->where('is_active', true);
                  });
            })
            ->orderBy('grade_level_id')
            ->orderBy('order_index');

        if ($request->has('grade_level_id')) {
            $query->where('grade_level_id', $request->grade_level_id);
        }

        return ApiResponse::ok($query->get());
    }

    public function show(Topic $topic)
    {
        $topic->load([
            'gradeLevel.subject',
            'videos',
            'quizzes' => function ($q) {
                $q->where('is_active', true)
                  ->orderBy('order_index');
            },
        ]);

        if (! $topic->gradeLevel?->is_active || ! $topic->gradeLevel?->subject?->is_active) {
            return ApiResponse::fail(
                'NOT_FOUND',
                'Topic is not available.',
                404
            );
        }

        return ApiResponse::ok($topic);
    }
}
