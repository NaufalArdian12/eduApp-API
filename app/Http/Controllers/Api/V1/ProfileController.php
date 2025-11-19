<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class   ProfileController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/v1/profile",
     *   tags={"Profile"},
     *   summary="Get authenticated user profile",
     *   security={{"BearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(
     *           property="user",
     *           type="object",
     *           description="Authenticated user",
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="name", type="string", example="Naufal Ardian"),
     *           @OA\Property(property="email", type="string", example="naufal@example.com"),
     *           @OA\Property(property="avatar_url", type="string", nullable=true, example="https://example.com/avatar.png"),
     *           @OA\Property(property="role", type="string", example="student"),
     *           @OA\Property(
     *             property="student_profile",
     *             type="object",
     *             nullable=true,
     *             @OA\Property(property="grade_level_id", type="integer", nullable=true, example=1),
     *             @OA\Property(
     *               property="grade_level",
     *               type="object",
     *               nullable=true,
     *               @OA\Property(property="id", type="integer", example=1),
     *               @OA\Property(property="name", type="string", example="Grade 1"),
     *               @OA\Property(property="grade_no", type="integer", example=1)
     *             ),
     *             @OA\Property(property="onboarding_completed", type="boolean", example=true)
     *           )
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function show(Request $req)
    {
        $user = $req->user()->load([
            'studentProfile.gradeLevel',
        ]);

        return ApiResponse::ok([
            'user' => $user,
        ]);
    }

    /**
     * @OA\Put(
     *   path="/api/v1/profile",
     *   tags={"Profile"},
     *   summary="Update authenticated user profile",
     *   security={{"BearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Naufal Ardian", maxLength=100),
     *       @OA\Property(property="avatar_url", type="string", nullable=true, example="https://example.com/avatar.png"),
     *       @OA\Property(property="grade_level_id", type="integer", nullable=true, example=1)
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(UpdateProfileRequest $req)
    {
        $val = $req->validated();
        $user = $req->user();

        $gradeLevelId = $val['grade_level_id'] ?? null;
        unset($val['grade_level_id']);

        if (!empty($val)) {
            $user->fill($val)->save();
        }

        if (!is_null($gradeLevelId)) {
            $user->studentProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'grade_level_id' => $gradeLevelId,
                    'onboarding_completed' => true,
                ]
            );
        }

        $user->load(['studentProfile.gradeLevel']);

        return ApiResponse::ok([
            'user' => $user,
        ]);
    }
}
