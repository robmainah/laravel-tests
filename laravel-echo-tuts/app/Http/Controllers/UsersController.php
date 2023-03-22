<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function store(User $user, Request $request)
    {
        // https://codeanddeploy.com/blog/laravel/laravel-supervisor-setup-with-example
        $user = User::find(1);

        dispatch(new \App\Jobs\UserInvitationJob($user));
    }
}
