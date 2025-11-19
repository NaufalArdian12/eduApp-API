<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTopicRequest;
use App\Models\Topic;
use App\Services\AdminContentService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function __construct(
        private AdminContentService $service
    ) {
    }

    public function index(Request $request)
    {
        $gradeLevelId = $request->query('grade_level_id');

        $topics = $this->service->listTopics(
            $gradeLevelId ? (int) $gradeLevelId : null
        );

        return ApiResponse::ok($topics);
    }

    public function store(StoreTopicRequest $request)
    {
        $data = $request->validated();

        $topic = $this->service->createTopic($data);

        return ApiResponse::ok($topic, null, 201);
    }

    public function show(Topic $topic)
    {
        $topic->load(['videos', 'quizzes']);

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

        $topic = $this->service->updateTopic($topic, $data);

        return ApiResponse::ok($topic);
    }

    public function destroy(Topic $topic)
    {
        $this->service->deleteTopic($topic);

        return ApiResponse::ok(null, null, 204);
    }
}
