<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_site_settings(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get(route('admin.site-settings.edit'))->assertForbidden();
    }

    public function test_admin_can_view_and_update_site_settings(): void
    {
        SiteSetting::query()->create([
            'site_url' => 'https://ideal-10.com',
            'lang' => 'es-CO',
            'title' => 'Ideal',
            'web3forms_key' => 'old-key',
        ]);
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get(route('admin.site-settings.edit'))->assertOk();

        $this->actingAs($admin)->put(route('admin.site-settings.update'), [
            'site_url' => 'https://ideal-10.com',
            'lang' => 'es-CO',
            'title' => 'Ideal',
            'web3forms_key' => 'new-key',
        ])->assertRedirect(route('admin.site-settings.edit'));

        $this->assertSame('new-key', SiteSetting::current()->web3forms_key);
    }
}
