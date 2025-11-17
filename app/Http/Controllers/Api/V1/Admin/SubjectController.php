<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
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

    public function store(StoreSubjectRequest $request)
    {
        $subject = $this->service->createSubject($request->validated());
        return ApiResponse::ok($subject, null, 201);
    }

    public function show(Subject $subject)
    {
        $subject->load('gradeLevels');

        return ApiResponse::ok($subject);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject = $this->service->updateSubject($subject, $request->validated());
        return ApiResponse::ok($subject);
    }
    public function destroy(Subject $subject)
    {
        $this->service->deleteSubject($subject);

        return ApiResponse::ok(null, null, 204);
    }
}
