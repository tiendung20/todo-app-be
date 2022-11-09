<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = Task::query()->where('user', $this->user['username'])->get()->sortBy('created_at');
        return response()->json(['msg' => 'SUCCESS', 'data' => TaskResource::collection($tasks)]);
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
            $task['user'] = $this->user['username'];
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
        $task = Task::findOrFail($id);
        if ($task['user'] !== $this->user['username']) return response()->json(['msg' => 'ERROR']);
        return response()->json(['msg' => 'SUCCESS', 'data' => new TaskResource($task)]);
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
        if ($task['user'] !== $this->user['username']) return response()->json(['msg' => 'ERROR']);
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
        if ($task['user'] !== $this->user['username']) return response()->json(['msg' => 'ERROR']);
        $task->delete();
        return response()->json(['msg' => 'SUCCESS', 'data' => new TaskResource($task)]);
    }
}
