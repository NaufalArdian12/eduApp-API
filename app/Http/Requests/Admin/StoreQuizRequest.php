<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class StoreQuizRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'prompt' => ['required', 'string'],
            'canonical_answer' => ['nullable', 'string'],
            'acceptable_answers' => ['nullable', 'array'],
            'numeric_tolerance' => ['nullable', 'numeric'],
            'eval_type' => ['nullable', 'in:semantic,exact,numeric'],
            'rubric_id' => ['nullable', 'exists:rubrics,id'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'topic_id.required' => 'The topic field is required.',
            'topic_id.exists' => 'The selected topic was not found.',

            'title.required' => 'The title must be filled in.',
            'title.string' => 'The title must be text.',
            'title.max' => 'The title cannot exceed 255 characters.',

            'prompt.required' => 'The prompt must be filled in.',
            'prompt.string' => 'The prompt must be text.',

            'canonical_answer.string' => 'The canonical answer must be in text form.',

            'acceptable_answers.array' => 'Acceptable answers must be in the form of an array.',

            'numeric_tolerance.numeric' => 'Numeric tolerance must be a number.',

            'eval_type.in' => 'The eval type must be one of the following: semantic, exact, numeric.',

            'rubric_id.exists' => 'The selected rubric was not found.',

            'order_index.integer' => 'The order index must be a number.',

            'is_active.boolean' => 'Is active must be a boolean (true/false).',
        ];
    }
}
