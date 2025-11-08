<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Support\ApiResponse;
use OpenApi\Annotations as OA;

class ProfileController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/v1/profile",
     *   tags={"Profile"},
     *   summary="Get profile",
     *   security={{"BearerAuth":{}}},
     *   @OA\Response(response=200, description="OK")
     * )
     */
    public function show(Request $req)
    {
        return ApiResponse::ok(['user' => $req->user()]);
    }

    /**
     * @OA\Put(
     *   path="/api/v1/profile",
     *   tags={"Profile"},
     *   summary="Update profile",
     *   security={{"BearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Naufal Ardian", maxLength=100)
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $req)
    {
        $val = $req->validate([
            // pakai sometimes agar field opsional, tapi kalau dikirim harus valid
            'name' => ['sometimes', 'string', 'max:100'],
        ]);

        $user = $req->user();
        if (!empty($val)) {
            $user->fill($val)->save();
        }

        return ApiResponse::ok(['user' => $user]);
    }
}
