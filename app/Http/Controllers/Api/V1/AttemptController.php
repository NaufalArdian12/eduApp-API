<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAttemptRequest;
use App\Http\Requests\Api\V1\StoreBatchAttemptRequest;
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

    /**
     * @OA\Post(
     *   path="api/v1/attempts",
     *   summary="Submit jawaban essay untuk dinilai AI",
     *   tags={"Attempts"},
     *   security={{"sanctum":{}}},
     *   @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          required={"quiz_id","answer"},
     *          @OA\Property(
     *              property="quiz_id",
     *              type="integer",
     *              example=1
     *          ),
     *          @OA\Property(
     *              property="answer",
     *              type="string",
     *              example="Saya menjumlahkan pecahan dengan penyebut yang sama, jadi 3/7 + 2/7 = 5/7."
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *      description="Attempt tersimpan dan sudah digrading AI",
     *      @OA\JsonContent(
     *          @OA\Property(property="status", type="string", example="success"),
     *          @OA\Property(
     *              property="data",
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=10),
     *              @OA\Property(property="quiz_id", type="integer", example=1),
     *              @OA\Property(property="user_id", type="integer", example=1),
     *              @OA\Property(property="attempt_no", type="integer", example=1),
     *              @OA\Property(property="status", type="string", example="graded"),
     *              @OA\Property(property="answer", type="string", example="...jawaban siswa..."),
     *              @OA\Property(property="label", type="string", example="UNDERSTOOD"),
     *              @OA\Property(property="ai_score_percent", type="integer", example=95),
     *              @OA\Property(property="ai_feedback", type="object"),
     *              @OA\Property(property="ai_model", type="string", example="gpt-4o-mini"),
     *              @OA\Property(property="created_at", type="string", format="date-time"),
     *              @OA\Property(property="updated_at", type="string", format="date-time")
     *          )
     *      )
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Validasi gagal"
     *   )
     * )
     */

    public function store(StoreAttemptRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

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
    public function storeBatch(StoreBatchAttemptRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();
        $answers = $data['answers'];

        $created = [];
        foreach ($answers as $item) {
            // reuse service (submitAttempt) — pastikan service bisa dipanggil berulang
            $attempt = $this->attemptService->submitAttempt(
                $user,
                $item['quiz_id'],
                $item['answer']
            );
            $created[] = $attempt;
        }

        return ApiResponse::ok($created, null, 201);
    }
}
