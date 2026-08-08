<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavItemControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_javascript_scheme_url(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.nav-items.store'), [
            'url' => 'javascript:alert(1)',
            'label' => 'Malicious',
        ]);

        $response->assertSessionHasErrors('url');
        $this->assertDatabaseMissing('nav_items', ['label' => 'Malicious']);
    }

    public function test_rejects_data_scheme_url(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.nav-items.store'), [
            'url' => 'data:text/html,<script>alert(1)</script>',
            'label' => 'Malicious',
        ]);

        $response->assertSessionHasErrors('url');
        $this->assertDatabaseMissing('nav_items', ['label' => 'Malicious']);
    }

    public function test_accepts_relative_path_url(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.nav-items.store'), [
            'url' => '/contacto',
            'label' => 'Contacto',
        ]);

        $response->assertSessionDoesntHaveErrors('url');
        $this->assertDatabaseHas('nav_items', ['url' => '/contacto']);
    }

    public function test_accepts_absolute_https_url(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.nav-items.store'), [
            'url' => 'https://example.com',
            'label' => 'External',
        ]);

        $response->assertSessionDoesntHaveErrors('url');
        $this->assertDatabaseHas('nav_items', ['url' => 'https://example.com']);
    }
}
