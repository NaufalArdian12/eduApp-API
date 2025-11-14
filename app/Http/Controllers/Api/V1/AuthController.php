<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Password};
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Support\ApiResponse;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
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
     */
    public function register(RegisterRequest $req)
    {
        $val = $req->validated();
        $user = User::create([
            'name' => $val['name'],
            'email' => $val['email'],
            'password' => Hash::make($val['password']),
        ]);

        if (
            config('auth.send_verify', env('AUTH_SEND_VERIFY', false))
            && method_exists($user, 'sendEmailVerificationNotification')
        ) {
            $user->sendEmailVerificationNotification();
        }

        return ApiResponse::ok(['user' => $user], null, 201);
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
    public function login(LoginRequest $req)
    {
        $val = $req->validated();

        $user = User::where('email', $val['email'])->first();
        if (!$user || !Hash::check($val['password'], $user->password)) {
            return ApiResponse::fail('INVALID_CREDENTIALS', 'Email atau password salah', 401);
        }

        if (config('auth.must_verified', false) && is_null($user->email_verified_at)) {
            return ApiResponse::fail('EMAIL_NOT_VERIFIED', 'Email belum terverifikasi', 403);
        }

        $abilities = ['*'];
        $expiresAt = now()->addDays(30);
        $token = $user->createToken($val['device_name'] ?? 'mobile', $abilities, $expiresAt);

        return ApiResponse::ok([
            'token' => $token->plainTextToken,
            'token_expires_at' => $expiresAt->toIso8601String(),
            'user' => $user,
        ]);
    }

    public function me(Request $req)
    {
        return ApiResponse::ok(['user' => $req->user()]);
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
        $token = $req->user()?->currentAccessToken();
        if ($token)
            $token->delete();

        return ApiResponse::ok(['message' => 'Logged out']);
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
        $current = $user?->currentAccessToken();

        if (!$user || !$current) {
            return ApiResponse::fail('NO_TOKEN', 'Tidak ada token aktif', 401);
        }

        $expiresAt = now()->addDays(30);
        $new = $user->createToken($current->name ?? 'mobile', $current->abilities ?? ['*'], $expiresAt);
        $current->delete();

        return ApiResponse::ok([
            'token' => $new->plainTextToken,
            'token_expires_at' => $expiresAt->toIso8601String(),
        ]);
    }

    public function changePassword(ChangePasswordRequest $req)
    {
        $val = $req->validated();

        $user = $req->user();
        if (!$user || !Hash::check($val['current_password'], $user->password)) {
            return ApiResponse::fail('WRONG_PASSWORD', 'Password saat ini salah', 422);
        }

        $user->forceFill([
            'password' => Hash::make($val['new_password']),
        ])->save();

        $user->tokens()->delete();

        return ApiResponse::ok(['message' => 'Password updated. Please login again.']);
    }

    public function forgotPassword(ForgotPasswordRequest $req)
    {
        $val = $req->validated();

        $user = User::where('email', $val['email'])->first();
        if (!$user) {
            return ApiResponse::ok(['message' => 'If exists, email sent']);
        }

        if (config('auth.reset_only_verified', true) && is_null($user->email_verified_at)) {
            return ApiResponse::fail('EMAIL_NOT_VERIFIED', 'Akun belum terverifikasi', 403);
        }

        $status = Password::sendResetLink(['email' => $val['email']]);

        return $status === Password::RESET_LINK_SENT
            ? ApiResponse::ok(['message' => __($status)])
            : ApiResponse::fail('RESET_FAILED', __($status), 500);
    }

    public function resetPassword(ResetPasswordRequest $req)
    {
        $val = $req->validated();

        $status = Password::reset(
            $val,
            function (User $user) use ($val) {
                $user->forceFill(['password' => Hash::make($val['password'])])->save();

                $user->tokens()->delete();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? ApiResponse::ok(['message' => __($status)])
            : ApiResponse::fail('RESET_FAILED', __($status), 400);
    }
}
