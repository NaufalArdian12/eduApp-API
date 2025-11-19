<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class UpdateGradeLevelRequest extends ApiFormRequest
{


    public function rules(): array
    {
        return [
            'grade_no' => ['sometimes', 'integer', 'min:1', 'max:12'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'order_index' => ['sometimes', 'nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'grade_no.integer' => 'Grade harus berupa angka.',
            'grade_no.min' => 'Grade minimal adalah 1.',
            'grade_no.max' => 'Grade maksimal adalah 12.',

            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

            'description.string' => 'Deskripsi harus berupa teks.',

            'order_index.integer' => 'Order index harus berupa angka.',

            'is_active.boolean' => 'Is active harus berupa boolean (true/false).',
        ];
    }
}
