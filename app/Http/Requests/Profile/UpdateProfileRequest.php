<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\ApiFormRequest;

class UpdateProfileRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'avatar_url' => ['sometimes', 'nullable', 'url'],
            'grade_level_id' => ['sometimes', 'nullable', 'exists:grade_levels,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The name must be a valid text.',
            'name.max' => 'The name may not be greater than 100 characters.',

            'avatar_url.url' => 'The avatar URL must be a valid URL.',

            'grade_level_id.exists' => 'The selected grade level is invalid.',
        ];
    }
}
