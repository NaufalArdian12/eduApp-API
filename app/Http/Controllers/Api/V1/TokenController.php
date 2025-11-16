<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function refresh(Request $req)
    {
        $req->validate(['refresh_token' => ['required', 'string']]);
        $hash = hash('sha256', $req->refresh_token);
        $row = RefreshToken::where('token_hash', $hash)->first();

        if (!$row || ($row->expires_at && now()->greaterThan($row->expires_at))) {
            return response()->json(['status' => 'fail', 'error' => ['code' => 'INVALID_REFRESH', 'message' => 'Invalid/expired refresh token']], 401);
        }

        $user = $row->user;
        $row->delete();

        $access = $user->createToken('movato-mobile')->plainTextToken;
        $newRefresh = Str::random(64);
        RefreshToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $newRefresh),
            'user_agent' => substr((string) $req->userAgent(), 0, 255),
            'ip' => $req->ip(),
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $access,
                'refresh_token' => $newRefresh,
                'expires_in' => 60 * 60 * 24
            ]
        ]);
    }

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()?->delete();
        return response()->noContent();
    }
}
