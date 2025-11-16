<?php

namespace App\Repositories;

use App\Models\GradeLevel;
use Illuminate\Support\Collection;

class GradeLevelRepository
{
    public function all(?int $subjectId = null): Collection
    {
        $query = GradeLevel::with('subject')
            ->orderBy('subject_id')
            ->orderBy('order_index');

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query->get();
    }

    public function find(int $id): ?GradeLevel
    {
        return GradeLevel::find($id);
    }

    public function create(array $data): GradeLevel
    {
        return GradeLevel::create($data);
    }

    public function update(GradeLevel $gradeLevel, array $data): GradeLevel
    {
        $gradeLevel->update($data);
        return $gradeLevel;
    }

    public function delete(GradeLevel $gradeLevel): void
    {
        $gradeLevel->delete();
    }
}
