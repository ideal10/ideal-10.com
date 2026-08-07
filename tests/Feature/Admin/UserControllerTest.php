<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_users_index(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_admin_can_view_users_index(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'nuevo@example.com', 'is_admin' => false]);
    }

    public function test_admin_can_promote_a_user_to_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $other = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)->put(route('admin.users.update', $other), [
            'name' => $other->name,
            'email' => $other->email,
            'is_admin' => '1',
        ]);

        $this->assertTrue($other->fresh()->is_admin);
    }

    public function test_leaving_password_blank_on_update_keeps_current_password(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $original = $admin->password;

        $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'is_admin' => '1',
        ]);

        $this->assertSame($original, $admin->fresh()->password);
    }

    public function test_admin_cannot_remove_their_own_admin_flag(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
        ]);

        $response->assertSessionHasErrors('is_admin');
        $this->assertTrue($admin->fresh()->is_admin);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $other = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $other));

        $this->assertDatabaseMissing('users', ['id' => $other->id]);
    }
}
