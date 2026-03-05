<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
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
    
    public function store(StoreTaskRequest $request, int $projectId): Task
    {
        $data = $request->validated();

        return  Task::create(array_merge($data, ['project_id' => $projectId])); 
    }

    public function show(Task $task): Task
    {
        return $task;
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();
        
        return $task->update($data);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
