<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class UpdateRubricRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'thresholds_json' => ['sometimes', 'nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Nama rubric maksimal 255 karakter.',
            'thresholds_json.array' => 'Thresholds harus dalam bentuk array.',
        ];
    }
}
