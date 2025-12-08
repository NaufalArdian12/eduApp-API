<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchAttemptRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user() != null;
    }

    public function rules()
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.quiz_id' => ['required', 'integer', 'exists:quizzes,id'],
            'answers.*.answer' => ['required', 'string'],
            // optional: topic_id kalau mau
        ];
    }
}