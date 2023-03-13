<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\TodoRequest;

class TodoListController extends Controller
{
    public function index()
    {
        return response(auth()->user()->todo_lists);
    }

    public function show(TodoList $todo_list)
    {
        return response($todo_list);
    }

    public function store(TodoRequest $request)
    {
        return auth()->user()->todo_lists()->create($request->validated());
    }

    public function update(TodoRequest $request, TodoList $todo_list)
    {
        $todo_list->update($request->all());

        return $todo_list;
    }

    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }
}
