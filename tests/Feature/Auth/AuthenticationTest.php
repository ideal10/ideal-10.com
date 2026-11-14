<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'login' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_users_can_authenticate_with_their_username(): void
    {
        $user = User::factory()->create(['username' => 'Omar.G']);

        $this->assertSame('omar.g', $user->username);

        $this->post('/login', ['login' => 'OMAR.G', 'password' => 'password']);

        $this->assertAuthenticatedAs($user);
    }

    public function test_users_without_username_still_sign_in_with_email(): void
    {
        $user = User::factory()->create(['username' => null]);

        $this->post('/login', ['login' => strtoupper($user->email), 'password' => 'password']);

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_username_is_rejected_on_the_login_field(): void
    {
        User::factory()->create(['username' => 'omar']);

        $this->post('/login', ['login' => 'otro', 'password' => 'password'])
            ->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_users_can_set_their_username_from_the_profile(): void
    {
        $user = User::factory()->create();
        User::factory()->create(['username' => 'ocupado']);

        $this->actingAs($user)->patch('/profile', ['name' => $user->name, 'email' => $user->email, 'username' => 'ocupado'])
            ->assertSessionHasErrors('username');
        $this->actingAs($user)->patch('/profile', ['name' => $user->name, 'email' => $user->email, 'username' => 'con@arroba'])
            ->assertSessionHasErrors('username');

        $this->actingAs($user)->patch('/profile', ['name' => $user->name, 'email' => $user->email, 'username' => 'Omar'])
            ->assertSessionHasNoErrors();

        $this->assertSame('omar', $user->refresh()->username);
    }
}
