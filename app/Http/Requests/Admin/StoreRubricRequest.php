<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class StoreRubricRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thresholds_json' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama rubric wajib diisi.',
            'name.max' => 'Nama rubric maksimal 255 karakter.',
            'thresholds_json.array' => 'Thresholds harus dalam bentuk array.',
        ];
    }
}
