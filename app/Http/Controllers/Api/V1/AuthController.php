<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Password, DB};
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    private function ok($data = null, $meta = null, int $code = 200) {
        $res = ['ok' => true, 'data' => $data];
        if (!is_null($meta)) $res['meta'] = $meta;
        return response()->json($res, $code);
    }
    private function fail(string $code, string $message, int $http = 400, $details = null) {
        $err = ['ok' => false, 'error' => ['code' => $code, 'message' => $message]];
        if (!is_null($details)) $err['error']['details'] = $details;
        return response()->json($err, $http);
    }

    public function register(Request $req) {
        $val = $req->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);
        $user = User::create([
            'name' => $val['name'],
            'email' => $val['email'],
            'password' => Hash::make($val['password']),
        ]);

        // (opsional) kirim verifikasi email
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        return $this->ok(['user' => $user], null, 201);
    }

    public function login(Request $req) {
        $val = $req->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string|max:100', // isi nama device dari mobile
        ]);

        $user = User::where('email', $val['email'])->first();
        if (!$user || !Hash::check($val['password'], $user->password)) {
            return $this->fail('INVALID_CREDENTIALS', 'Email atau password salah', 401);
        }

        // (opsional) wajib email terverifikasi
        if (config('auth.must_verified', false) && is_null($user->email_verified_at)) {
            return $this->fail('EMAIL_NOT_VERIFIED', 'Email belum terverifikasi', 403);
        }

        // buat token (bisa set abilities dan expiry)
        $abilities = ['*']; // atau granular: ['read', 'write']
        $expiresAt = now()->addDays(30); // token kadaluarsa 30 hari (mobile)
        $token = $user->createToken($val['device_name'] ?? 'mobile', $abilities, $expiresAt);

        return $this->ok([
            'token' => $token->plainTextToken,
            'token_expires_at' => $expiresAt->toIso8601String(),
            'user' => $user,
        ]);
    }

    public function me(Request $req) {
        return $this->ok(['user' => $req->user()]);
    }

    public function logout(Request $req) {
        $req->user()->currentAccessToken()->delete();
        return $this->ok(['message' => 'Logged out']);
    }

    // Rotasi token manual: buat baru, hapus yang lama
    public function refresh(Request $req) {
        $user = $req->user();
        $current = $user->currentAccessToken();
        if (!$current) return $this->fail('NO_TOKEN', 'Tidak ada token aktif', 401);

        $expiresAt = now()->addDays(30);
        $new = $user->createToken($current->name ?? 'mobile', $current->abilities ?? ['*'], $expiresAt);
        $current->delete();

        return $this->ok([
            'token' => $new->plainTextToken,
            'token_expires_at' => $expiresAt->toIso8601String(),
        ]);
    }

    public function changePassword(Request $req) {
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

        // revoke token lama & paksa re-login
        $user->tokens()->delete();

        return $this->ok(['message' => 'Password updated. Please login again.']);
    }

    public function forgotPassword(Request $req) {
        $val = $req->validate(['email' => 'required|email']);
        // (opsional) hanya untuk email terverifikasi
        $user = User::where('email', $val['email'])->first();
        if (!$user) return $this->ok(['message' => 'If exists, email sent']); // jangan bocorkan user existence
        if (config('auth.reset_only_verified', true) && is_null($user->email_verified_at)) {
            return $this->fail('EMAIL_NOT_VERIFIED', 'Akun belum terverifikasi', 403);
        }
        $status = Password::sendResetLink(['email' => $val['email']]);
        return $status === Password::RESET_LINK_SENT
            ? $this->ok(['message' => __($status)])
            : $this->fail('RESET_FAILED', __($status), 500);
    }

    public function resetPassword(Request $req) {
        $val = $req->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ]);

        $status = Password::reset(
            $val,
            function (User $user) use ($val) {
                $user->forceFill(['password' => Hash::make($val['password'])])->save();
                $user->tokens()->delete(); // revoke semua token
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? $this->ok(['message' => __($status)])
            : $this->fail('RESET_FAILED', __($status), 400);
    }
}
