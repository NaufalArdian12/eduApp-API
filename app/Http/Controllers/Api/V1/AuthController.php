<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Password, DB};
use OpenApi\Annotations as OA;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{

    private function ok($data = null, $meta = null, int $code = 200)
    {
        $res = ['ok' => true, 'data' => $data];
        if (!is_null($meta))
            $res['meta'] = $meta;
        return response()->json($res, $code);
    }
    private function fail(string $code, string $message, int $http = 400, $details = null)
    {
        $err = ['ok' => false, 'error' => ['code' => $code, 'message' => $message]];
        if (!is_null($details))
            $err['error']['details'] = $details;
        return response()->json($err, $http);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/auth/register",
     *   tags={"Auth"},
     *   summary="Register user",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name","email","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", example="Naufal"),
     *       @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *       @OA\Property(property="password", type="string", format="password", example="Passw0rd!"),
     *       @OA\Property(property="password_confirmation", type="string", example="Passw0rd!")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Created")
     * )
     * /**
     */
    public function register(Request $req)
    {


        $val = $req->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email:rfc|unique:users,email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);
        $user = User::create([
            'name' => $val['name'],
            'email' => $val['email'],
            'password' => Hash::make($val['password']),
        ]);

        if (env('AUTH_SEND_VERIFY', false) && method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }


        return $this->ok(['user' => $user], null, 201);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/auth/login",
     *   tags={"Auth"},
     *   summary="Login & dapat Bearer token",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", example="user@example.com"),
     *       @OA\Property(property="password", type="string", example="Passw0rd!"),
     *       @OA\Property(property="device_name", type="string", example="mobile")
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Invalid credentials")
     * )
     */

    public function login(Request $req)
    {

        $val = $req->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string|max:100', // isi nama device dari mobile
        ]);

        $user = User::where('email', $val['email'])->first();
        if (!$user || !Hash::check($val['password'], $user->password)) {
            return $this->fail('INVALID_CREDENTIALS', 'Email atau password salah', 401);
        }

        if (config('auth.must_verified', false) && is_null($user->email_verified_at)) {
            return $this->fail('EMAIL_NOT_VERIFIED', 'Email belum terverifikasi', 403);
        }

        $abilities = ['*'];
        $expiresAt = now()->addDays(30);
        $token = $user->createToken($val['device_name'] ?? 'mobile', $abilities, $expiresAt);

        return $this->ok([
            'token' => $token->plainTextToken,
            'token_expires_at' => $expiresAt->toIso8601String(),
            'user' => $user,
        ]);
    }

    public function me(Request $req)
    {
        return $this->ok(['user' => $req->user()]);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/auth/logout",
     *   tags={"Auth"},
     *   summary="Revoke token (logout)",
     *   security={{"BearerAuth":{}}},
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */

    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return $this->ok(['message' => 'Logged out']);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/auth/refresh",
     *   tags={"Auth"},
     *   summary="Rotate token",
     *   security={{"BearerAuth":{}}},
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */

    public function refresh(Request $req)
    {
        $user = $req->user();
        $current = $user->currentAccessToken();
        if (!$current)
            return $this->fail('NO_TOKEN', 'Tidak ada token aktif', 401);

        $expiresAt = now()->addDays(30);
        $new = $user->createToken($current->name ?? 'mobile', $current->abilities ?? ['*'], $expiresAt);
        $current->delete();

        return $this->ok([
            'token' => $new->plainTextToken,
            'token_expires_at' => $expiresAt->toIso8601String(),
        ]);
    }

    public function changePassword(Request $req)
    {
        $val = $req->validate([
            'current_password' => 'required',
            'new_password' => ['required', PasswordRule::min(8)->mixedCase()->numbers(), 'confirmed'],
        ]);
        $user = $req->user();
        if (!Hash::check($val['current_password'], $user->password)) {
            return $this->fail('WRONG_PASSWORD', 'Password saat ini salah', 422);
        }
        $user->forceFill([
            'password' => Hash::make($val['new_password']),
        ])->save();

        $user->tokens()->delete();

        return $this->ok(['message' => 'Password updated. Please login again.']);
    }

    public function forgotPassword(Request $req)
    {
        $val = $req->validate(['email' => 'required|email']);
        $user = User::where('email', $val['email'])->first();
        if (!$user)
            return $this->ok(['message' => 'If exists, email sent']);
        if (config('auth.reset_only_verified', true) && is_null($user->email_verified_at)) {
            return $this->fail('EMAIL_NOT_VERIFIED', 'Akun belum terverifikasi', 403);
        }
        $status = Password::sendResetLink(['email' => $val['email']]);
        return $status === Password::RESET_LINK_SENT
            ? $this->ok(['message' => __($status)])
            : $this->fail('RESET_FAILED', __($status), 500);
    }

    public function resetPassword(Request $req)
    {
        $val = $req->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);

        $status = Password::reset(
            $val,
            function (User $user) use ($val) {
                $user->forceFill(['password' => Hash::make($val['password'])])->save();
                $user->tokens()->delete();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? $this->ok(['message' => __($status)])
            : $this->fail('RESET_FAILED', __($status), 400);
    }
}
