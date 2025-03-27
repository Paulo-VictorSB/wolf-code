<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{

    public function index()
    {   
        $tasks = Task::all();
        return TaskResource::collection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        if (empty($data['status'])) {
            $data['status'] = 'pendente';
        }
        $tasks = Task::create($data);
        return new TaskResource($tasks);
    }

    public function update(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());
        return new TaskResource($task->fresh());
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'task deleted'
        ]);
    }
}
