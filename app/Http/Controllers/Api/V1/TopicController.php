<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $query = Topic::with(['gradeLevel.subject'])
            ->orderBy('grade_level_id')
            ->orderBy('order_index');

        if ($request->has('grade_level_id')) {
            $query->where('grade_level_id', $request->grade_level_id);
        }

        return ApiResponse::ok($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grade_level_id' => ['required', 'exists:grade_levels,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order_index' => ['nullable', 'integer'],
            'min_videos_before_assessment' => ['nullable', 'integer', 'min:0'],
            'is_assessment_enabled' => ['boolean'],
        ]);

        $topic = Topic::create($data);

        return ApiResponse::ok($topic, null, 201);
    }

    public function show(Topic $topic)
    {
        $topic->load(['gradeLevel.subject', 'videos', 'quizzes']);

        return ApiResponse::ok($topic);
    }

    public function update(Request $request, Topic $topic)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'order_index' => ['sometimes', 'nullable', 'integer'],
            'min_videos_before_assessment' => ['sometimes', 'integer', 'min:0'],
            'is_assessment_enabled' => ['sometimes', 'boolean'],
        ]);

        $topic->update($data);

        return ApiResponse::ok($topic);
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return ApiResponse::ok(null, null, 204);
    }
}
