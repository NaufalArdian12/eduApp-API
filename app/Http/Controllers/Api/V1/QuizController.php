<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $query = Quiz::query()
            ->with('topic')
            ->where('is_active', true)
            ->whereHas('topic', function ($q) {
                $q->where('is_active', true);
            });

        if ($request->has('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        $quizzes = $query
            ->orderBy('topic_id')
            ->orderBy('order_index')
            ->get()
            ->makeHidden([
                'canonical_answer',
                'acceptable_answers',
                'numeric_tolerance',
                'eval_type',
                'rubric_id',
                'created_at',
                'updated_at',
            ]);

        return ApiResponse::ok($quizzes);
    }

    public function show(Quiz $quiz)
    {
        if (!$quiz->is_active || !$quiz->topic?->is_active) {
            return ApiResponse::fail(
                'NOT_FOUND',
                'Quiz is not available.',
                404
            );
        }

        $quiz->load('topic');

        $quiz->makeHidden([
            'canonical_answer',
            'acceptable_answers',
            'numeric_tolerance',
            'eval_type',
            'rubric_id',
            'created_at',
            'updated_at',
        ]);

        return ApiResponse::ok($quiz);
    }
}
