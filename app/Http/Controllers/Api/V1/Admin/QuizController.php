<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Services\AdminContentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(
        private AdminContentService $service
    ) {
    }

    public function index(Request $request)
    {
        $topicId = $request->query('topic_id');

        $quizzes = $this->service->listQuizzes(
            $topicId ? (int) $topicId : null
        );

        return ApiResponse::ok($quizzes);
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

        $quiz = $this->service->createQuiz($data);

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

        $quiz = $this->service->updateQuiz($quiz, $data);

        return ApiResponse::ok($quiz);
    }

    public function destroy(Quiz $quiz)
    {
        $this->service->deleteQuiz($quiz);

        return ApiResponse::ok(null, null, 204);
    }
}
