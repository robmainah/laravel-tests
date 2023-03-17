<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index() 
    {
        return User::latest()->get();
    }

    public function store() 
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        return User::create($data);
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required',
        ]);

        $user->update($data);
        
        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();        
        return response()->json(['message' => 'User deleted successfully']);
    }
}
