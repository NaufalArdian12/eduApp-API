<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Quiz;
use App\Services\AiGradingService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class DebugAiController extends Controller
{
    public function __construct(
        private AiGradingService $aiGrading,
    ) {
    }

    public function gradeSample(Request $request)
    {
        $data = $request->validate([
            'prompt' => ['required', 'string'],
            'canonical_answer' => ['nullable', 'string'],
            'acceptable_answers' => ['nullable', 'array'],
            'eval_type' => ['nullable', 'in:semantic,exact,numeric'],
            'numeric_tolerance' => ['nullable', 'numeric'],
            'student_answer' => ['required', 'string'],
        ]);

        $quiz = new Quiz([
            'prompt' => $data['prompt'],
            'canonical_answer' => $data['canonical_answer'] ?? null,
            'acceptable_answers' => $data['acceptable_answers'] ?? [],
            'eval_type' => $data['eval_type'] ?? 'semantic',
            'numeric_tolerance' => $data['numeric_tolerance'] ?? null,
        ]);

        $attempt = new Attempt([
            'answer' => $data['student_answer'],
        ]);

        $result = $this->aiGrading->grade($quiz, $attempt);

        return ApiResponse::ok($result);
    }
}
