<?php

namespace App\Repositories;

use App\Models\Attempt;

class AttemptRepository
{
    public function getLastAttemptNo(int $userId, int $quizId): int
    {
        return Attempt::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->max('attempt_no') ?? 0;
    }

    public function create(array $data): Attempt
    {
        return Attempt::create($data);
    }

    public function update(Attempt $attempt, array $data): Attempt
    {
        $attempt->update($data);
        return $attempt;
    }
}
