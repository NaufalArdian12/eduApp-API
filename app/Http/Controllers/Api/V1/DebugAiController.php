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

    /**
     * @OA\Post(
     *   path="/api/v1/debug/grade",
     *   summary="Coba AI grading secara manual (debug)",
     *   tags={"Debug"},
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          required={"prompt","canonical_answer","student_answer"},
     *          @OA\Property(
     *              property="prompt",
     *              type="string",
     *              example="Hitung 12 + 8 dan jelaskan langkahmu."
     *          ),
     *          @OA\Property(
     *              property="canonical_answer",
     *              type="string",
     *              example="20"
     *          ),
     *          @OA\Property(
     *              property="acceptable_answers",
     *              type="array",
     *              @OA\Items(type="string"),
     *              example={"20", "dua puluh"}
     *          ),
     *          @OA\Property(
     *              property="eval_type",
     *              type="string",
     *              enum={"semantic","exact","numeric"},
     *              example="semantic"
     *          ),
     *          @OA\Property(
     *              property="student_answer",
     *              type="string",
     *              example="Saya tahu 12 + 8 = 20 karena 10 + 10 = 20 dan 2 + 8 = 10."
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Hasil grading AI",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(
     *              property="data",
     *              ref="object"
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validasi gagal"
     *   )
     * )
     */

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
