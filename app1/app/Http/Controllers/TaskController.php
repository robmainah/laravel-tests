<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TodoList;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    public function index(TodoList $todo_list)
    {
        return response($todo_list->tasks);
    }

    public function store(TaskRequest $request, TodoList $todo_list)
    {
        return $todo_list->tasks()->create($request->validated());
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return response($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
