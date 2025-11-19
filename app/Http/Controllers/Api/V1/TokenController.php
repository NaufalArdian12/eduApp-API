<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function refresh(Request $request)
    {
        $data = $request->validate([
            'refresh_token' => ['required', 'string'],
        ]);

        $hash = hash('sha256', $data['refresh_token']);
        $row = RefreshToken::where('token_hash', $hash)->first();

        if (!$row || ($row->expires_at && now()->greaterThan($row->expires_at))) {
            if ($row) {
                $row->delete();
            }

            return ApiResponse::fail(
                'INVALID_REFRESH',
                'Invalid or expired refresh token.',
                401
            );
        }

        $user = $row->user;

        $row->delete();

        $accessToken = $user->createToken('movato-mobile')->plainTextToken;

        $newRefresh = Str::random(64);
        RefreshToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $newRefresh),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'ip' => $request->ip(),
            'expires_at' => now()->addDays(30),
        ]);

        $ttlMinutes = config('sanctum.expiration');
        $expiresIn = $ttlMinutes ? $ttlMinutes * 60 : null;

        return ApiResponse::ok([
            'access_token' => $accessToken,
            'refresh_token' => $newRefresh,
            'token_type' => 'Bearer',
            'expires_in' => $expiresIn,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->noContent();
    }
}
