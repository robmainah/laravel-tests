<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Post $post) 
    {
        return response()->json($post->comments()->with('user')->latest()->get());
    }

    public function store(Post $post) 
    {
        $comment = $post->comments()->create([
            'body' => request('body'),
            'user_id' => 1,
        ]);

        $comment = Comment::where('id', $comment->id)->with('user')->first();

        return $comment->toJson();
    }
}
