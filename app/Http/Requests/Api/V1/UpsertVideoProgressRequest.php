<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpsertVideoProgressRequest extends FormRequest
{


    public function rules(): array
    {
        return [
            'video_id' => ['required', 'exists:videos,id'],
            'seconds_watched' => ['required', 'integer', 'min:0'],
            'is_completed' => ['sometimes', 'boolean'],
        ];
    }
}
