<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoListResource;

class TodoListController extends Controller
{
    public function index()
    {
        $todo_lists = auth()->user()->todo_lists;
        return response(TodoListResource::collection($todo_lists));
    }

    public function show(TodoList $todo_list)
    {
        return new TodoListResource($todo_list);
    }

    public function store(TodoRequest $request)
    {
        $todo_list = auth()->user()->todo_lists()->create($request->validated());
        return new TodoListResource($todo_list);
    }

    public function update(TodoRequest $request, TodoList $todo_list)
    {
        $todo_list->update($request->all());

        return new TodoListResource($todo_list);
    }

    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
