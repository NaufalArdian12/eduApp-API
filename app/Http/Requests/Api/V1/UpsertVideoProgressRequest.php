<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\ApiFormRequest;

class UpsertVideoProgressRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'video_id' => ['required', 'exists:videos,id'],
            'seconds_watched' => ['required', 'integer', 'min:0'],
            'is_completed' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'video_id.required' => 'The video ID is required.',
            'video_id.exists'   => 'The selected video does not exist.',

            'seconds_watched.required' => 'The watched duration is required.',
            'seconds_watched.integer'  => 'The watched duration must be an integer (seconds).',
            'seconds_watched.min'      => 'The watched duration cannot be negative.',

            'is_completed.boolean' => 'The completion flag must be true or false.',
        ];
    }
}
