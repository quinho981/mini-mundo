<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(): Collection
    {
        return $this->projectService->getAll();
    }

    public function store(StoreProjectRequest $request): Project
    {
        $data = $request->validated();

        return $this->projectService->create($data);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json($project);
    }

    public function update(UpdateProjectRequest $request, int $id): Project
    {
        $data = $request->validated();

        return $this->projectService->update($data, $id);
    }

    public function destroy(Project $project): JsonResponse
    {
        if($project->hasLinkedTask()) {
            return response()->json([
                'message' => 'This project has linked tasks and cannot be deleted.'
            ], 422);
        }

        $project->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
