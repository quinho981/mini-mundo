<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
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
}
