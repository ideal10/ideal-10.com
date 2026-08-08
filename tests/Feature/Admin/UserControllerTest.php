<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_users_index(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.users.index'))->assertOk();
    }

    public function test_can_create_a_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.users.store'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'nuevo@example.com']);
    }

    public function test_leaving_password_blank_on_update_keeps_current_password(): void
    {
        $user = User::factory()->create();
        $original = $user->password;

        $this->actingAs($user)->put(route('admin.users.update', $user), [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->assertSame($original, $user->fresh()->password);
    }

    public function test_cannot_delete_own_account(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->delete(route('admin.users.destroy', $user))->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_can_delete_another_user(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($user)->delete(route('admin.users.destroy', $other));

        $this->assertDatabaseMissing('users', ['id' => $other->id]);
    }
}
