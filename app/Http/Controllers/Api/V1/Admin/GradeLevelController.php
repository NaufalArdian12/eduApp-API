<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGradeLevelRequest;
use App\Http\Requests\Admin\UpdateGradeLevelRequest;
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

    public function store(StoreGradeLevelRequest $request)
    {
        $data = $request->validated();

        $gradeLevel = $this->service->createGradeLevel($data);

        return ApiResponse::ok($gradeLevel, null, 201);
    }

    public function show(GradeLevel $gradeLevel)
    {
        $gradeLevel->load('topics');

        return ApiResponse::ok($gradeLevel);
    }

    public function update(UpdateGradeLevelRequest $request, GradeLevel $gradeLevel)
    {
        $data = $request->validated();

        $gradeLevel = $this->service->updateGradeLevel($gradeLevel, $data);

        return ApiResponse::ok($gradeLevel);
    }

    public function destroy(GradeLevel $gradeLevel)
    {
        $this->service->deleteGradeLevel($gradeLevel);

        return ApiResponse::ok([
            'message' => 'Grade level berhasil dihapus.'
        ], null, 200);
    }
}
