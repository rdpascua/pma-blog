<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Repositories\EloquentPostRepository;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_interface_is_bound_to_the_eloquent_class(): void
    {
        $repository = $this->app->make(PostRepository::class);

        $this->assertInstanceOf(EloquentPostRepository::class, $repository);
    }

    public function test_all_returns_the_newest_post_first(): void
    {
        $old = Post::create(['title' => 'Old', 'body' => 'Text']);
        $old->created_at = now()->subDay();
        $old->save();
        Post::create(['title' => 'New', 'body' => 'Text']);

        $posts = (new EloquentPostRepository)->all();

        $this->assertSame('New', $posts[0]->title);
        $this->assertSame('Old', $posts[1]->title);
    }

    public function test_create_saves_a_post(): void
    {
        $post = (new EloquentPostRepository)->create(['title' => 'Hello', 'body' => 'Text']);

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Hello']);
    }

    public function test_update_changes_a_post(): void
    {
        $post = Post::create(['title' => 'Hello', 'body' => 'Text']);

        (new EloquentPostRepository)->update($post, ['title' => 'Changed', 'body' => 'Text']);

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Changed']);
    }

    public function test_delete_removes_a_post(): void
    {
        $post = Post::create(['title' => 'Hello', 'body' => 'Text']);

        (new EloquentPostRepository)->delete($post);

        $this->assertDatabaseCount('posts', 0);
    }
}
