<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class UpdateTopicRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'order_index' => ['sometimes', 'nullable', 'integer'],
            'min_videos_before_assessment' => ['sometimes', 'integer', 'min:0'],
            'is_assessment_enabled' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Judul topik maksimal 255 karakter.',
            'order_index.integer' => 'Urutan harus berupa angka.',
            'min_videos_before_assessment.integer' => 'Minimal video harus berupa angka.',
            'min_videos_before_assessment.min' => 'Minimal video tidak boleh kurang dari 0.',
            'is_assessment_enabled.boolean' => 'Status assessment harus berupa boolean.',
        ];
    }
}
