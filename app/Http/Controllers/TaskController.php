<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['msg' => 'SUCCESS', 'data' => TaskResource::collection(Task::all()->sortBy('created_at'))]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->filled(['label', 'done'])) {
            $task = $request->only('label', 'done');
            $task['done'] = filter_var($task['done'], FILTER_VALIDATE_BOOLEAN);
            return response()->json(['msg' => 'SUCCESS', 'data' => new TaskResource(Task::create($task))]);
        }
        return response()->json(['msg' => 'ERROR']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json(['msg' => 'SUCCESS', 'data' => new TaskResource(Task::findOrFail($id))]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $todo = $request->only('label', 'done');
        if ($request->has('done')) {
            $todo['done'] = filter_var($todo['done'], FILTER_VALIDATE_BOOLEAN);;
        }
        $task->update($todo);
        return response()->json(['msg' => 'SUCCESS', 'data' => new TaskResource($task)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['msg' => 'SUCCESS', 'data' => new TaskResource($task)]);
    }
}
