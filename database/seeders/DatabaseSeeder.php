<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        Post::create([
            'title' => 'First post',
            'body' => 'Hello from the blog.',
        ]);

        Post::create([
            'title' => 'Second post',
            'body' => 'Log in to add, edit and delete posts.',
        ]);
    }
}
