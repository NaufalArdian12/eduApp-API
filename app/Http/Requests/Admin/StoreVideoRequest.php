<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class StoreVideoRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'youtube_id' => ['required', 'string', 'max:255'],
            'youtube_url' => ['required', 'url'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'topic_id.required' => 'Topik wajib diisi.',
            'topic_id.exists' => 'Topik yang dipilih tidak ditemukan.',
            'title.required' => 'Judul video wajib diisi.',
            'title.max' => 'Judul video maksimal 255 karakter.',
            'youtube_id.required' => 'Youtube ID wajib diisi.',
            'youtube_url.required' => 'Youtube URL wajib diisi.',
            'youtube_url.url' => 'Youtube URL tidak valid.',
            'duration_seconds.integer' => 'Durasi harus berupa angka (detik).',
            'duration_seconds.min' => 'Durasi tidak boleh negatif.',
            'is_active.boolean' => 'Status aktif harus berupa boolean.',
        ];
    }
}
