<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_in_then_create_edit_and_delete_a_post(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $this->get('/posts/create')->assertRedirect('/login');

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ])->assertRedirect('/posts');

        $this->post('/posts', [
            'title' => 'First post',
            'body' => 'Hello',
        ])->assertRedirect('/posts');

        $this->get('/posts')->assertSee('First post')->assertSee('Edit');

        $post = Post::first();

        $this->put("/posts/{$post->id}", [
            'title' => 'Edited post',
            'body' => 'Hello again',
        ])->assertRedirect('/posts');

        $this->get('/posts')->assertSee('Edited post')->assertDontSee('First post');

        $this->delete("/posts/{$post->id}")->assertRedirect('/posts');

        $this->get('/posts')->assertDontSee('Edited post');

        $this->post('/logout')->assertRedirect('/login');

        $this->get('/posts/create')->assertRedirect('/login');
    }
}
