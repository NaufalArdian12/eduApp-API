<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class StoreGradeLevelRequest extends ApiFormRequest
{

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade_no' => ['required', 'integer', 'min:1', 'max:12'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject_id.required' => 'Subject must be filled in.',
            'subject_id.exists'   => 'The selected subject was not found.',
            'grade_no.min'        => 'The minimum grade is 1.',
            'grade_no.max'        => 'The maximum grade is 12.',
        ];
    }
}
