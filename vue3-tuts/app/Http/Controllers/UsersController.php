<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;

class UsersController extends Controller
{
    public function index() 
    {
        return UserResource::collection(User::latest()->get());
    }

    public function store() 
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        return new UserResource(User::create(request()->all()));
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required',
        ]);

        $user->update($data);
        
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $user->delete();    
        return response()->json(['message' => 'User deleted successfully']);
    }
}
