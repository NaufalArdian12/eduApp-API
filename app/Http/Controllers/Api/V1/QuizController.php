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
        $query = Quiz::with('topic');

        if ($request->has('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        return ApiResponse::ok(
            $query->orderBy('topic_id')->orderBy('order_index')->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'prompt' => ['required', 'string'],
            'canonical_answer' => ['nullable', 'string'],
            'acceptable_answers' => ['nullable', 'array'],
            'numeric_tolerance' => ['nullable', 'numeric'],
            'eval_type' => ['nullable', 'in:semantic,exact,numeric'],
            'rubric_id' => ['nullable', 'exists:rubrics,id'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $quiz = Quiz::create($data);

        return ApiResponse::ok($quiz, null, 201);
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('topic');

        return ApiResponse::ok($quiz);
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'prompt' => ['sometimes', 'string'],
            'canonical_answer' => ['sometimes', 'nullable', 'string'],
            'acceptable_answers' => ['sometimes', 'nullable', 'array'],
            'numeric_tolerance' => ['sometimes', 'nullable', 'numeric'],
            'eval_type' => ['sometimes', 'in:semantic,exact,numeric'],
            'rubric_id' => ['sometimes', 'nullable', 'exists:rubrics,id'],
            'order_index' => ['sometimes', 'nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $quiz->update($data);

        return ApiResponse::ok($quiz);
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return ApiResponse::ok(null, null, 204);
    }
}
