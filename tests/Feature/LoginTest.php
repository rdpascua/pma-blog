<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
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

    public function test_the_login_form_opens(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_the_password_is_stored_hashed(): void
    {
        $user = $this->user();

        $this->assertNotSame('secret123', $user->password);
    }

    public function test_a_user_can_log_in(): void
    {
        $user = $this->user();

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/posts');
        $this->assertAuthenticatedAs($user);
    }

    public function test_a_wrong_password_is_rejected(): void
    {
        $this->user();

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_an_unknown_email_is_rejected(): void
    {
        $response = $this->post('/login', [
            'email' => 'nobody@example.com',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_the_login_form_is_validated(): void
    {
        $response = $this->post('/login', [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_a_user_can_log_out(): void
    {
        $response = $this->actingAs($this->user())->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
