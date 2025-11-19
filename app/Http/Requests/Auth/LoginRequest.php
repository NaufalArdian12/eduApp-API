<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{



        public function stopOnFirstFailure(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
            'device_name' => is_string($this->device_name) ? trim($this->device_name) : $this->device_name,
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => ['required','email'],
            'password' => ['required'],
            'device_name' => ['nullable','string','max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'email',
            'password' => 'kata sandi',
            'device_name' => 'nama perangkat',
        ];
    }
}
