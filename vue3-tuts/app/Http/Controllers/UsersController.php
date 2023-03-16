<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return User::create($data);
    }
}
