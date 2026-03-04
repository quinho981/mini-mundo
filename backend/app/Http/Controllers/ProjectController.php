<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        return $this->projectService->getAll();
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        return $this->projectService->create($data);
    }

    public function show(Project $project)
    {
        return response()->json($project);
    }

    public function update(UpdateProjectRequest $request, int $id)
    {
        $data = $request->validated();

        return $this->projectService->update($data, $id);
    }
}
