<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks for a project.
     */
    public function index(Request $request, $projectId)
    {
        try {
            $project = $request->user()
                ->projects()
                ->findOrFail($projectId);

            $tasks = $project->tasks()->latest()->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $tasks
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request, $projectId)
    {
        try {
            $project = $request->user()
                ->projects()
                ->findOrFail($projectId);

            $task = $project->tasks()->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'data' => $task
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified task.
     */
    public function show(Request $request, $projectId, $taskId)
    {
        try {
            $project = $request->user()
                ->projects()
                ->findOrFail($projectId);

            $task = $project->tasks()->findOrFail($taskId);

            return response()->json([
                'success' => true,
                'data' => $task
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project or task not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, $projectId, $taskId)
    {
        try {
            $project = $request->user()
                ->projects()
                ->findOrFail($projectId);

            $task = $project->tasks()->findOrFail($taskId);
            $task->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => $task
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project or task not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Request $request, $projectId, $taskId)
    {
        try {
            $project = $request->user()
                ->projects()
                ->findOrFail($projectId);

            $task = $project->tasks()->findOrFail($taskId);
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project or task not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
