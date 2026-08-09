<?php

namespace Tests\Feature\Admin;

use App\Models\SupportPhone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportPhoneControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.support-phones.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_a_support_phone(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.support-phones.store'), [
            'number' => '320 249 7418',
            'type' => 'whatsapp',
            'order' => 1,
            'active' => 1,
        ]);

        $response->assertRedirect(route('admin.support-phones.index'));
        $this->assertDatabaseHas('support_phones', [
            'number' => '320 249 7418',
            'type' => 'whatsapp',
            'active' => 1,
        ]);
    }

    public function test_store_rejects_a_number_that_is_too_short(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.support-phones.store'), [
            'number' => '12345',
            'type' => 'whatsapp',
        ]);

        $response->assertSessionHasErrors('number');
        $this->assertDatabaseMissing('support_phones', ['number' => '12345']);
    }

    public function test_store_rejects_an_invalid_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.support-phones.store'), [
            'number' => '320 249 7418',
            'type' => 'sms',
        ]);

        $response->assertSessionHasErrors('type');
        $this->assertDatabaseMissing('support_phones', ['number' => '320 249 7418']);
    }

    public function test_admin_can_update_a_support_phone(): void
    {
        $user = User::factory()->create();
        $phone = SupportPhone::create(['number' => '320 249 7418', 'type' => 'whatsapp', 'order' => 1, 'active' => true]);

        $response = $this->actingAs($user)->put(route('admin.support-phones.update', $phone), [
            'number' => '312 586 3064',
            'type' => 'dial',
            'order' => 2,
            'active' => 0,
        ]);

        $response->assertRedirect(route('admin.support-phones.index'));
        $this->assertDatabaseHas('support_phones', [
            'id' => $phone->id,
            'number' => '312 586 3064',
            'type' => 'dial',
            'active' => 0,
        ]);
    }

    public function test_admin_can_delete_a_support_phone(): void
    {
        $user = User::factory()->create();
        $phone = SupportPhone::create(['number' => '320 249 7418', 'type' => 'whatsapp', 'order' => 1, 'active' => true]);

        $response = $this->actingAs($user)->delete(route('admin.support-phones.destroy', $phone));

        $response->assertRedirect(route('admin.support-phones.index'));
        $this->assertDatabaseMissing('support_phones', ['id' => $phone->id]);
    }

    public function test_admin_can_toggle_active_state(): void
    {
        $user = User::factory()->create();
        $phone = SupportPhone::create(['number' => '320 249 7418', 'type' => 'whatsapp', 'order' => 1, 'active' => true]);

        $response = $this->actingAs($user)->patch(route('admin.support-phones.toggle', $phone));

        $response->assertRedirect(route('admin.support-phones.index'));
        $this->assertFalse($phone->fresh()->active);
    }

    public function test_admin_can_reorder_support_phones(): void
    {
        $user = User::factory()->create();
        $first = SupportPhone::create(['number' => '320 249 7418', 'type' => 'whatsapp', 'order' => 1, 'active' => true]);
        $second = SupportPhone::create(['number' => '312 586 3064', 'type' => 'whatsapp', 'order' => 2, 'active' => true]);

        $response = $this->actingAs($user)->post(route('admin.support-phones.reorder'), [
            'ids' => [$second->id, $first->id],
        ]);

        $response->assertRedirect(route('admin.support-phones.index'));
        $this->assertSame(1, $second->fresh()->order);
        $this->assertSame(2, $first->fresh()->order);
    }
}
