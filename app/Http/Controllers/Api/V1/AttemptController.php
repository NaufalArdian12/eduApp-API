<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Services\AttemptService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function __construct(
        private AttemptService $attemptService
    ) {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Attempt::with('quiz.topic')
            ->where('user_id', $user->id)
            ->latest();

        if ($request->has('quiz_id')) {
            $query->where('quiz_id', $request->quiz_id);
        }

        return ApiResponse::ok($query->get());
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'quiz_id' => ['required', 'integer', 'exists:quizzes,id'],
            'answer' => ['required', 'string'],
        ]);

        $attempt = $this->attemptService->submitAttempt(
            $user,
            $data['quiz_id'],
            $data['answer'],
        );

        return ApiResponse::ok($attempt, null, 201);
    }

    public function show(Request $request, Attempt $attempt)
    {
        $user = $request->user();

        if ($attempt->user_id !== $user->id) {
            return ApiResponse::fail('FORBIDDEN', 'You cannot access this attempt', 403);
        }

        $attempt->load('quiz.topic');

        return ApiResponse::ok($attempt);
    }
}
