<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $tasks = Task::create($request->validated());
        return new TaskResource($tasks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);

        if ($task->status != 'pendente') {
            return response()->json([
                'success' => false,
                'message' => 'not is possible update tasks at not is pending'
            ], 403);
        }

        $task->update($request->validated());
        return new TaskResource($task->fresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        // Verifica se a tarefa não está pendente
        if ($task->status != 'pendente') {
            return response()->json([
                'success' => false,
                'message' => 'not is possible delete tasks at not is pending'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'task deleted'
        ]);
    }
}
