<?php

namespace App\Http\Controllers;

use App\Events\NewComment;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentController extends Controller
{
    public function index(Post $post) 
    {
        return $post->comments()->with('user:id,name')->latest()->paginate(5);
    }

    public function store(Post $post) 
    {
        $comment = $post->comments()->create([
            'body' => request('body'),
            'user_id' => User::all()->random()->id,
        ]);

        $comment = Comment::where('id', $comment->id)->with('user:id,name')->first();

        broadcast(new NewComment($comment))->toOthers();

        return $comment->toJson();
    }
}
