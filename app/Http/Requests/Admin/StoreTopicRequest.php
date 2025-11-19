<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class StoreTopicRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order_index' => ['nullable', 'integer'],
            'min_videos_before_assessment' => ['nullable', 'integer', 'min:0'],
            'is_assessment_enabled' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'grade_level_id.required' => 'Grade level wajib diisi.',
            'grade_level_id.exists' => 'Grade level yang dipilih tidak ditemukan.',
            'title.required' => 'Judul topik wajib diisi.',
            'title.max' => 'Judul topik maksimal 255 karakter.',
            'min_videos_before_assessment.integer' => 'Minimal video harus berupa angka.',
            'min_videos_before_assessment.min' => 'Minimal video tidak boleh kurang dari 0.',
            'is_assessment_enabled.boolean' => 'Status assessment harus berupa boolean.',
        ];
    }
}
