<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class UpdateVideoRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'youtube_id' => ['sometimes', 'string', 'max:255'],
            'youtube_url' => ['sometimes', 'url'],
            'duration_seconds' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'order_index' => ['sometimes', 'nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Judul video maksimal 255 karakter.',
            'youtube_id.max' => 'Youtube ID maksimal 255 karakter.',
            'youtube_url.url' => 'Youtube URL tidak valid.',
            'duration_seconds.integer' => 'Durasi harus berupa angka (detik).',
            'duration_seconds.min' => 'Durasi tidak boleh negatif.',
            'order_index.integer' => 'Urutan harus berupa angka.',
            'is_active.boolean' => 'Status aktif harus berupa boolean.',
        ];
    }
}
