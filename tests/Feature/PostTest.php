<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);
    }

    public function test_the_list_shows_a_post(): void
    {
        Post::create(['title' => 'Hello', 'body' => 'Text']);

        $response = $this->get('/posts');

        $response->assertStatus(200);
        $response->assertSee('Hello');
    }

    public function test_the_list_escapes_html_in_a_post(): void
    {
        Post::create(['title' => '<b>Bold</b>', 'body' => 'Text']);

        $response = $this->get('/posts');

        $response->assertSee('&lt;b&gt;Bold&lt;/b&gt;', false);
    }

    public function test_a_guest_does_not_see_the_edit_and_delete_buttons(): void
    {
        Post::create(['title' => 'Hello', 'body' => 'Text']);

        $response = $this->get('/posts');

        $response->assertDontSee('Edit');
        $response->assertDontSee('Delete');
    }

    public function test_a_guest_cannot_open_the_form(): void
    {
        $response = $this->get('/posts/create');

        $response->assertRedirect('/login');
    }

    public function test_a_guest_cannot_change_posts(): void
    {
        $post = Post::create(['title' => 'Hello', 'body' => 'Text']);

        $this->post('/posts', ['title' => 'New', 'body' => 'Text'])->assertRedirect('/login');
        $this->get("/posts/{$post->id}/edit")->assertRedirect('/login');
        $this->put("/posts/{$post->id}", ['title' => 'Changed', 'body' => 'Text'])->assertRedirect('/login');
        $this->delete("/posts/{$post->id}")->assertRedirect('/login');

        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseHas('posts', ['title' => 'Hello']);
    }

    public function test_a_user_can_open_the_form(): void
    {
        $response = $this->actingAs($this->user())->get('/posts/create');

        $response->assertStatus(200);
    }

    public function test_a_user_can_add_a_post(): void
    {
        $response = $this->actingAs($this->user())->post('/posts', [
            'title' => 'New post',
            'body' => 'Text',
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', ['title' => 'New post']);
    }

    public function test_a_post_needs_a_title_and_a_body(): void
    {
        $response = $this->actingAs($this->user())->post('/posts', [
            'title' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_a_title_cannot_be_longer_than_120_characters(): void
    {
        $response = $this->actingAs($this->user())->post('/posts', [
            'title' => str_repeat('a', 121),
            'body' => 'Text',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_the_edit_form_shows_the_current_values(): void
    {
        $post = Post::create(['title' => 'Hello', 'body' => 'Text']);

        $response = $this->actingAs($this->user())->get("/posts/{$post->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Hello');
    }

    public function test_a_user_can_update_a_post(): void
    {
        $post = Post::create(['title' => 'Hello', 'body' => 'Text']);

        $response = $this->actingAs($this->user())->put("/posts/{$post->id}", [
            'title' => 'Changed',
            'body' => 'New text',
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Changed']);
    }

    public function test_an_update_is_validated(): void
    {
        $post = Post::create(['title' => 'Hello', 'body' => 'Text']);

        $response = $this->actingAs($this->user())->put("/posts/{$post->id}", [
            'title' => '',
            'body' => 'New text',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Hello']);
    }

    public function test_a_user_can_delete_a_post(): void
    {
        $post = Post::create(['title' => 'Hello', 'body' => 'Text']);

        $response = $this->actingAs($this->user())->delete("/posts/{$post->id}");

        $response->assertRedirect('/posts');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_a_missing_post_gives_a_404(): void
    {
        $response = $this->actingAs($this->user())->get('/posts/999/edit');

        $response->assertStatus(404);
    }
}
