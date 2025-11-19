<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ChangePasswordRequest extends FormRequest
{


    public function stopOnFirstFailure(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required'],
            'new_password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()],
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password' => 'password saat ini',
            'new_password' => 'password baru',
        ];
    }
}
