<?php

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Support\Collection;

class VideoRepository
{
    public function all(?int $topicId = null): Collection
    {
        $query = Video::with('topic')
            ->orderBy('topic_id')
            ->orderBy('order_index');

        if ($topicId) {
            $query->where('topic_id', $topicId);
        }

        return $query->get();
    }

    public function find(int $id): ?Video
    {
        return Video::find($id);
    }

    public function create(array $data): Video
    {
        return Video::create($data);
    }

    public function update(Video $video, array $data): Video
    {
        $video->update($data);
        return $video;
    }

    public function delete(Video $video): void
    {
        $video->delete();
    }
}
