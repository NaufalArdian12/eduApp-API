<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreQuizRequest;
use App\Http\Requests\Admin\UpdateQuizRequest;
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

    public function store(StoreQuizRequest $request)
    {
        $data = $request->validated();

        $quiz = $this->service->createQuiz($data);

        return ApiResponse::ok($quiz, null, 201);
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('topic');

        return ApiResponse::ok($quiz);
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz)
    {
        $data = $request->validated();

        $quiz = $this->service->updateQuiz($quiz, $data);

        return ApiResponse::ok($quiz);
    }

    public function destroy(Quiz $quiz)
    {
        $this->service->deleteQuiz($quiz);

        return ApiResponse::ok(null, null, 204);
    }
}
