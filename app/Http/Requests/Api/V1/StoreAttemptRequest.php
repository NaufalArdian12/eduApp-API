<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttemptRequest extends FormRequest
{


    public function rules(): array
    {
        return [
            'quiz_id' => ['required', 'exists:quizzes,id'],
            'answer' => ['required', 'string', 'max:5000'],
        ];
    }
}
