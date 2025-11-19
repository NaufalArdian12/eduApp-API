<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiFormRequest;

class UpdateQuizRequest extends ApiFormRequest
{


    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'prompt' => ['sometimes', 'string'],
            'canonical_answer' => ['sometimes', 'nullable', 'string'],
            'acceptable_answers' => ['sometimes', 'nullable', 'array'],
            'numeric_tolerance' => ['sometimes', 'nullable', 'numeric'],
            'eval_type' => ['sometimes', 'in:semantic,exact,numeric'],
            'rubric_id' => ['sometimes', 'nullable', 'exists:rubrics,id'],
            'order_index' => ['sometimes', 'nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',

            'prompt.string' => 'Prompt harus berupa teks.',

            'canonical_answer.string' => 'Jawaban kanonik harus berupa teks.',

            'acceptable_answers.array' => 'Acceptable answers harus berupa array.',

            'numeric_tolerance.numeric' => 'Numeric tolerance harus berupa angka.',

            'eval_type.in' => 'Eval type harus salah satu dari: semantic, exact, numeric.',

            'rubric_id.exists' => 'Rubric yang dipilih tidak ditemukan.',

            'order_index.integer' => 'Order index harus berupa angka.',

            'is_active.boolean' => 'Is active harus berupa boolean (true/false).'
        ];
    }
}
