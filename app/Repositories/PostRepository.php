<?php

namespace App\Repositories;

use App\Models\Post;

interface PostRepository
{
    public function all();

    public function create(array $data): Post;

    public function update(Post $post, array $data): void;

    public function delete(Post $post): void;
}
