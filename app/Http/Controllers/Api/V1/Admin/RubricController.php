<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRubricRequest;
use App\Http\Requests\Admin\UpdateRubricRequest;
use App\Models\Rubric;
use App\Services\AdminContentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class RubricController extends Controller
{
    public function __construct(
        private AdminContentService $service
    ) {
    }

    public function index()
    {
        return ApiResponse::ok(
            $this->service->listRubrics()
        );
    }

    public function store(StoreRubricRequest $request)
    {
        $data = $request->validated();

        $rubric = $this->service->createRubric($data);

        return ApiResponse::ok($rubric, null, 201);
    }

    public function show(Rubric $rubric)
    {
        return ApiResponse::ok($rubric);
    }

    public function update(UpdateRubricRequest $request, Rubric $rubric)
    {
        $data = $request->validated();

        $rubric = $this->service->updateRubric($rubric, $data);

        return ApiResponse::ok($rubric);
    }

    public function destroy(Rubric $rubric)
    {
        $this->service->deleteRubric($rubric);

        return ApiResponse::ok(null, null, 204);
    }

}
