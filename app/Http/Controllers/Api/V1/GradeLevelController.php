<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    public function index(Request $request)
    {
        $query = GradeLevel::with('subject')
            ->orderBy('subject_id')
            ->orderBy('order_index');

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        return ApiResponse::ok($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'grade_no' => ['required', 'integer', 'min:1', 'max:12'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $gradeLevel = GradeLevel::create($data);

        return ApiResponse::ok($gradeLevel, null, 201);
    }

    public function show(GradeLevel $gradeLevel)
    {
        $gradeLevel->load(['subject', 'topics']);

        return ApiResponse::ok($gradeLevel);
    }

    public function update(Request $request, GradeLevel $gradeLevel)
    {
        $data = $request->validate([
            'grade_no' => ['sometimes', 'integer', 'min:1', 'max:12'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'order_index' => ['sometimes', 'nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $gradeLevel->update($data);

        return ApiResponse::ok($gradeLevel);
    }

    public function destroy(GradeLevel $gradeLevel)
    {
        $gradeLevel->delete();

        return ApiResponse::ok(null, null, 204);
    }
}
