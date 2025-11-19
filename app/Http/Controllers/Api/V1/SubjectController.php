<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Support\ApiResponse;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return ApiResponse::ok($subjects);
    }

    public function show(Subject $subject)
    {
        if (!$subject->is_active) {
            return ApiResponse::fail(
                'NOT_FOUND',
                'Subject is not available.',
                404
            );
        }

        $subject->load([
            'gradeLevels' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('order_index');
            },
        ]);

        return ApiResponse::ok($subject);
    }
}
