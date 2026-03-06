<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(): Collection
    {
        return Task::all();   
    }
    
    public function store(StoreTaskRequest $request, Project $project): Task
    {
        $task = $project->tasks()->create($request->validated());

        return $task;
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function update(UpdateTaskRequest $request, Task $task): Task
    {
        $data = $request->validated();
        $task->update($data);
        
        return $task;
    }

    public function destroy(Task $task): JsonResponse
    {
        if ($task->isPredecessor()) {
            return response()->json([
                'message' => 'This task has dependent tasks and cannot be deleted.'
            ], 422);
        }    

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function getTaskByProject(Project $project)
    {
        return $project->tasks()->get();   
    }
}
