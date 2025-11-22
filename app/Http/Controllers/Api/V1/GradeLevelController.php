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
        $query = GradeLevel::query()
            ->with('subject')
            ->where('is_active', true)
            ->orderBy('subject_id')
            ->orderBy('order_index');

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // if ($user = $request->user()) {
        //     $query->where('grade_no', $user->grade_no);
        // }

        return ApiResponse::ok($query->get());
    }

    public function show(GradeLevel $gradeLevel)
    {
        if (! $gradeLevel->is_active) {
            return ApiResponse::fail(
                'NOT_FOUND',
                'Grade level is not available.',
                404
            );
        }

        $gradeLevel->load(['subject ', 'topics']);

        return ApiResponse::ok($gradeLevel);
    }
}
