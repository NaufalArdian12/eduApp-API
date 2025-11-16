<?php

namespace App\Repositories;

use App\Models\Subject;
use Illuminate\Support\Collection;

class SubjectRepository
{
    public function all(): Collection
    {
        return Subject::orderBy('id')->get();
    }

    public function find(int $id): ?Subject
    {
        return Subject::find($id);
    }

    public function create(array $data): Subject
    {
        return Subject::create($data);
    }

    public function update(Subject $subject, array $data): Subject
    {
        $subject->update($data);
        return $subject;
    }

    public function delete(Subject $subject): void
    {
        $subject->delete();
    }
}
