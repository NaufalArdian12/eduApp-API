<?php

namespace App\Repositories;

use App\Models\Rubric;
use Illuminate\Support\Collection;

class RubricRepository
{
    public function all(): Collection
    {
        return Rubric::orderBy('name')->get();
    }

    public function find(string $id): ?Rubric
    {
        return Rubric::find($id);
    }

    public function create(array $data): Rubric
    {
        return Rubric::create($data);
    }

    public function update(Rubric $rubric, array $data): Rubric
    {
        $rubric->update($data);
        return $rubric;
    }

    public function delete(Rubric $rubric): void
    {
        $rubric->delete();
    }
}
