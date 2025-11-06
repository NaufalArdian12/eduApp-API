<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ProfileController extends Controller
{
    // helper TANPA anotasi
    private function ok($data = null, $meta = null, int $code = 200)
    {
        $res = ['ok' => true, 'data' => $data];
        if (!is_null($meta)) $res['meta'] = $meta;
        return response()->json($res, $code);
    }

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
        return $this->ok(['user' => $req->user()]);
    }

    /**
     * @OA\Put(
     *   path="/api/v1/profile",
     *   tags={"Profile"},
     *   summary="Update profile",
     *   security={{"BearerAuth":{}}},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Naufal Ardian")
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $req)
    {
        $val = $req->validate([
            'name' => 'sometimes|string|max:100',
        ]);
        $user = $req->user();
        $user->fill($val)->save();

        return $this->ok(['user' => $user]);
    }
}
