<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['show']]);
    }

    public function index()
    {
        return Post::latest()->paginate(25);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $user = User::all()->random();

        $post = $user->posts()->create([
          'title' => $request->title,
          'content' => $request->content,
          'published' => $request->has('published')
        ]);

        return $post;
    }

    public function show($id) 
    {
        $post = Post::findOrFail($id);
        return $post;
    }

    public function edit($id)
    {
        return Post::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->published = ($request->has('published') ? true : false);
        $post->save();

        return $post;
    }

    public function destroy($id)
    {
        Post::destroy($id);
    }
}
