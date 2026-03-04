<?php

namespace App\Services;

use App\Models\Project;

class ProjectService
{
    public function getAll()
    {
        return Project::all();
    }
}