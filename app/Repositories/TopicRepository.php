<?php

namespace App\Repositories;

use App\Models\Topic;
use Illuminate\Support\Collection;

class TopicRepository
{
    public function all(?int $gradeLevelId = null): Collection
    {
        $query = Topic::with('gradeLevel.subject')
            ->orderBy('grade_level_id')
            ->orderBy('order_index');

        if ($gradeLevelId) {
            $query->where('grade_level_id', $gradeLevelId);
        }

        return $query->get();
    }

    public function find(int $id): ?Topic
    {
        return Topic::find($id);
    }

    public function create(array $data): Topic
    {
        return Topic::create($data);
    }

    public function update(Topic $topic, array $data): Topic
    {
        $topic->update($data);
        return $topic;
    }

    public function delete(Topic $topic): void
    {
        $topic->delete();
    }
}
