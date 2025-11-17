<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * Kumpulan global schema untuk Movato API.
 *
 * @OA\Schema(
 *   schema="ApiSuccess",
 *   @OA\Property(property="status", type="string", example="success"),
 *   @OA\Property(property="data")
 * )
 *
 * @OA\Schema(
 *   schema="ApiError",
 *   @OA\Property(property="status", type="string", example="error"),
 *   @OA\Property(
 *      property="error",
 *      type="object",
 *      @OA\Property(property="message", type="string", example="Something went wrong"),
 *      @OA\Property(property="details", type="object", nullable=true)
 *   )
 * )
 *
 * @OA\Schema(
 *   schema="AiGradeResult",
 *   @OA\Property(property="label", type="string", example="UNDERSTOOD"),
 *   @OA\Property(property="score", type="integer", example=95),
 *   @OA\Property(
 *      property="feedback",
 *      type="object",
 *      @OA\Property(property="feedback_summary", type="string"),
 *      @OA\Property(
 *          property="feedback_points",
 *          type="array",
 *          @OA\Items(type="string")
 *      ),
 *      @OA\Property(property="misunderstanding_type", type="string", nullable=true),
 *      @OA\Property(
 *          property="flags",
 *          type="object",
 *          @OA\Property(property="has_explanation", type="boolean"),
 *          @OA\Property(property="only_final_answer", type="boolean")
 *      )
 *   ),
 *   @OA\Property(property="ai_model", type="string", example="gpt-4o-mini")
 * )
 */
class Schemas
{
    // kosong, cuma buat nempel annotation
}
