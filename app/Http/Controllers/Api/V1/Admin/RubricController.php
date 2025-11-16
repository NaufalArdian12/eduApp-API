<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'thresholds_json' => ['nullable', 'array'],
        ]);

        $rubric = $this->service->createRubric($data);

        return ApiResponse::ok($rubric, null, 201);
    }

    public function show(Rubric $rubric)
    {
        return ApiResponse::ok($rubric);
    }

    public function update(Request $request, Rubric $rubric)
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string', 'nullable'],
            'thresholds_json' => ['sometimes', 'array', 'nullable'],
        ]);

        $rubric = $this->service->updateRubric($rubric, $data);

        return ApiResponse::ok($rubric);
    }

    public function destroy(Rubric $rubric)
    {
        $this->service->deleteRubric($rubric);

        return ApiResponse::ok(null, null, 204);
    }

}
