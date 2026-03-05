<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        return Task::all();   
    }
    
    public function store(StoreTaskRequest $request, int $projectId)
    {
        $data = $request->validated();

        return  Task::create(array_merge($data, ['project_id' => $projectId])); 
    }
}
