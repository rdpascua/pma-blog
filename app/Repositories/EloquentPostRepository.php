<?php

namespace App\Repositories;

use App\Models\Post;

class EloquentPostRepository implements PostRepository
{
    public function all()
    {
        return Post::latest()->get();
    }

    public function create(array $data): Post
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data): void
    {
        $post->update($data);
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }
}
