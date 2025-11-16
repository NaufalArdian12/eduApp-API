<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::query()
            ->orderBy('id')
            ->get();

        return ApiResponse::ok($subjects);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['boolean'],
        ]);

        $subject = Subject::create($data);

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
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $subject->update($data);

        return ApiResponse::ok($subject);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return ApiResponse::ok(null, null, 204);
    }
}
