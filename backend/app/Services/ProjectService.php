<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    public function getAll(): Collection
    {
        return Project::all();
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(array $data, int $id): Project
    {
        $project = Project::findOrFail($id);
        $project->update($data);

        return $project;
    }
}