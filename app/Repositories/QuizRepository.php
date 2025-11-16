<?php

namespace App\Repositories;

use App\Models\Quiz;
use Illuminate\Support\Collection;

class QuizRepository
{
    public function all(?int $topicId = null): Collection
    {
        $query = Quiz::with('topic');

        if ($topicId) {
            $query->where('topic_id', $topicId);
        }

        return $query->orderBy('topic_id')->orderBy('order_index')->get();
    }

    public function find(int $id): ?Quiz
    {
        return Quiz::find($id);
    }

    public function create(array $data): Quiz
    {
        return Quiz::create($data);
    }

    public function update(Quiz $quiz, array $data): Quiz
    {
        $quiz->update($data);
        return $quiz;
    }

    public function delete(Quiz $quiz): void
    {
        $quiz->delete();
    }
}
