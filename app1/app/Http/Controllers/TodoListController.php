<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $lists = TodoList::all();

        return response($lists);
    }

    public function show(TodoList $list)
    {
        return response($list);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        return TodoList::create(['name' => $request->name]);
    }

    public function update(Request $request, TodoList $list)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $list->update($request->all());

        return $list;
    }

    public function destroy(TodoList $list)
    {
        $list->delete();

        return response('', Response::HTTP_NO_CONTENT);
    }
}
