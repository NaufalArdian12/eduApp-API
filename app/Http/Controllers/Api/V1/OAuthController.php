<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\RefreshToken;
use App\Models\SocialAccount;
use App\Models\User;
use Carbon\CarbonImmutable;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    private function verify(string $idToken): array
    {
        $google = new GoogleClient(['client_id' => config('services.google.client_id')]);
        $payload = $google->verifyIdToken($idToken);
        if (!$payload)
            abort(401, 'Invalid Google ID token');
        $sub = $payload['sub'] ?? null;
        $email = $payload['email'] ?? null;
        if (!$sub || !$email)
            abort(422, 'Missing required claims');
        return $payload;
    }

    public function exchange(Request $req)
    {
        $req->validate(['id_token' => ['required', 'string']]);
        $payload = $this->verify($req->id_token);

        $sub = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'] ?? Str::before($email, '@');
        $picture = $payload['picture'] ?? null;
        $emailVerified = (bool) ($payload['email_verified'] ?? false);

        return DB::transaction(function () use ($sub, $email, $name, $picture, $payload, $emailVerified, $req) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => $emailVerified ? now() : null,
                ]);
            } else if (!$user->email_verified_at && $emailVerified) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            SocialAccount::updateOrCreate(
                ['provider' => 'google', 'provider_id' => $sub],
                ['user_id' => $user->id, 'avatar' => $picture, 'raw' => $payload]
            );

            $access = $user->createToken('movato-mobile')->plainTextToken;
            $refreshPlain = Str::random(64);
            RefreshToken::create([
                'user_id' => $user->id,
                'token_hash' => hash('sha256', $refreshPlain),
                'user_agent' => substr((string) request()->userAgent(), 0, 255),
                'ip' => request()->ip(),
                'expires_at' => CarbonImmutable::now()->addDays(30),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'access_token' => $access,
                    'refresh_token' => $refreshPlain,
                    'expires_in' => 60 * 60 * 24,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $picture
                    ]
                ]
            ]);
        });
    }

    public function link(Request $req)
    {
        $req->validate(['id_token' => ['required', 'string']]);
        $payload = $this->verify($req->id_token);

        $sub = $payload['sub'];
        $email = $payload['email'];
        $picture = $payload['picture'] ?? null;

        $user = $req->user();

        $exists = SocialAccount::where('provider', 'google')->where('provider_id', $sub)->first();
        if ($exists && $exists->user_id !== $user->id) {
            return response()->json(['status' => 'fail', 'error' => ['code' => 'LINK_CONFLICT', 'message' => 'This Google account is already linked to another user']], 409);
        }

        SocialAccount::updateOrCreate(
            ['provider' => 'google', 'provider_id' => $sub],
            ['user_id' => $user->id, 'avatar' => $picture, 'raw' => $payload]
        );

        return response()->json(['status' => 'success', 'data' => ['linked' => true]]);
    }
}
