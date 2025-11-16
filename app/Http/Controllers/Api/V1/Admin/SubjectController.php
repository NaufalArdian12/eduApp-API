<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Services\AdminContentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(
        private AdminContentService $service
    ) {
    }

    public function index()
    {
        $subjects = $serviceSubjects = $this->service->listSubjects();

        return ApiResponse::ok($subjects);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $subject = $this->service->createSubject($data);

        return ApiResponse::ok($subject, null, 201);
    }

    public function show(Subject $subject)
    {
        $subject->load('gradeLevels');

        return ApiResponse::ok($subject);
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $subject = $this->service->updateSubject($subject, $data);

        return ApiResponse::ok($subject);
    }

    public function destroy(Subject $subject)
    {
        $this->service->deleteSubject($subject);

        return ApiResponse::ok(null, null, 204);
    }
}
