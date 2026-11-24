<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostRepository $posts) {}

    public function index()
    {
        $posts = $this->posts->all();

        return view('posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'max:120'],
            'body' => ['required'],
        ]);

        $this->posts->create($data);

        return redirect('/posts');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required', 'max:120'],
            'body' => ['required'],
        ]);

        $this->posts->update($post, $data);

        return redirect('/posts');
    }

    public function destroy(Post $post)
    {
        $this->posts->delete($post);

        return redirect('/posts');
    }
}
