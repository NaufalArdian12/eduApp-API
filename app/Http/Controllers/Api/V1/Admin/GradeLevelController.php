<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradeLevel;
use App\Services\AdminContentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    public function __construct(
        private AdminContentService $service
    ) {
    }

    public function index(Request $request)
    {
        $subjectId = $request->query('subject_id');

        $gradeLevels = $this->service->listGradeLevels(
            $subjectId ? (int) $subjectId : null
        );

        return ApiResponse::ok($gradeLevels);
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

        $gradeLevel = $this->service->createGradeLevel($data);

        return ApiResponse::ok($gradeLevel, null, 201);
    }

    public function show(GradeLevel $gradeLevel)
    {
        $gradeLevel->load('topics');

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

        $gradeLevel = $this->service->updateGradeLevel($gradeLevel, $data);

        return ApiResponse::ok($gradeLevel);
    }

    public function destroy(GradeLevel $gradeLevel)
    {
        $this->service->deleteGradeLevel($gradeLevel);

        return ApiResponse::ok(null, null, 204);
    }
}
