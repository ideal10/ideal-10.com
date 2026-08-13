<?php

namespace Tests\Feature\Admin;

use App\Models\InterestLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterestLinkControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.interest-links.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_sort_links_by_title(): void
    {
        $response = $this->post(route('admin.interest-links.sort-by-title'), ['direction' => 'asc']);

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_sort_links_alphabetically_ascending(): void
    {
        $user = User::factory()->create();
        $charlie = InterestLink::create(['title' => 'Charlie', 'url' => 'https://example.com', 'order' => 1, 'active' => true]);
        $alpha = InterestLink::create(['title' => 'Alpha', 'url' => 'https://example.com', 'order' => 2, 'active' => true]);
        $bravo = InterestLink::create(['title' => 'Bravo', 'url' => 'https://example.com', 'order' => 3, 'active' => true]);

        $response = $this->actingAs($user)->post(route('admin.interest-links.sort-by-title'), ['direction' => 'asc']);

        $response->assertRedirect(route('admin.interest-links.index'));
        $this->assertSame(1, $alpha->fresh()->order);
        $this->assertSame(2, $bravo->fresh()->order);
        $this->assertSame(3, $charlie->fresh()->order);
    }

    public function test_admin_can_sort_links_alphabetically_descending(): void
    {
        $user = User::factory()->create();
        $charlie = InterestLink::create(['title' => 'Charlie', 'url' => 'https://example.com', 'order' => 1, 'active' => true]);
        $alpha = InterestLink::create(['title' => 'Alpha', 'url' => 'https://example.com', 'order' => 2, 'active' => true]);
        $bravo = InterestLink::create(['title' => 'Bravo', 'url' => 'https://example.com', 'order' => 3, 'active' => true]);

        $response = $this->actingAs($user)->post(route('admin.interest-links.sort-by-title'), ['direction' => 'desc']);

        $response->assertRedirect(route('admin.interest-links.index'));
        $this->assertSame(1, $charlie->fresh()->order);
        $this->assertSame(2, $bravo->fresh()->order);
        $this->assertSame(3, $alpha->fresh()->order);
    }

    public function test_sort_by_title_is_case_insensitive(): void
    {
        $user = User::factory()->create();
        $upper = InterestLink::create(['title' => 'zebra', 'url' => 'https://example.com', 'order' => 1, 'active' => true]);
        $lower = InterestLink::create(['title' => 'Apple', 'url' => 'https://example.com', 'order' => 2, 'active' => true]);

        $this->actingAs($user)->post(route('admin.interest-links.sort-by-title'), ['direction' => 'asc']);

        $this->assertSame(1, $lower->fresh()->order);
        $this->assertSame(2, $upper->fresh()->order);
    }

    public function test_sort_by_title_treats_unknown_direction_as_ascending(): void
    {
        $user = User::factory()->create();
        $charlie = InterestLink::create(['title' => 'Charlie', 'url' => 'https://example.com', 'order' => 1, 'active' => true]);
        $alpha = InterestLink::create(['title' => 'Alpha', 'url' => 'https://example.com', 'order' => 2, 'active' => true]);

        $this->actingAs($user)->post(route('admin.interest-links.sort-by-title'), ['direction' => 'not-a-real-direction']);

        $this->assertSame(1, $alpha->fresh()->order);
        $this->assertSame(2, $charlie->fresh()->order);
    }

    public function test_sort_by_title_never_produces_duplicate_or_skipped_order_values(): void
    {
        $user = User::factory()->create();
        InterestLink::create(['title' => 'Same title', 'url' => 'https://example.com', 'order' => 5, 'active' => true]);
        InterestLink::create(['title' => 'Same title', 'url' => 'https://example.com', 'order' => 5, 'active' => true]);
        InterestLink::create(['title' => 'Another', 'url' => 'https://example.com', 'order' => 5, 'active' => true]);

        $this->actingAs($user)->post(route('admin.interest-links.sort-by-title'), ['direction' => 'asc']);

        $orders = InterestLink::query()->orderBy('order')->pluck('order')->all();
        $this->assertSame([1, 2, 3], $orders);
    }

    public function test_manual_reorder_still_works_after_sorting_by_title(): void
    {
        $user = User::factory()->create();
        $charlie = InterestLink::create(['title' => 'Charlie', 'url' => 'https://example.com', 'order' => 1, 'active' => true]);
        $alpha = InterestLink::create(['title' => 'Alpha', 'url' => 'https://example.com', 'order' => 2, 'active' => true]);
        $bravo = InterestLink::create(['title' => 'Bravo', 'url' => 'https://example.com', 'order' => 3, 'active' => true]);

        $this->actingAs($user)->post(route('admin.interest-links.sort-by-title'), ['direction' => 'asc']);
        // Alphabetical order is now: alpha(1), bravo(2), charlie(3).

        $response = $this->actingAs($user)->post(route('admin.interest-links.reorder'), [
            'ids' => [$bravo->id, $alpha->id, $charlie->id],
        ]);

        $response->assertRedirect(route('admin.interest-links.index'));
        $this->assertSame(1, $bravo->fresh()->order);
        $this->assertSame(2, $alpha->fresh()->order);
        $this->assertSame(3, $charlie->fresh()->order);
    }

    public function test_index_still_orders_by_order_column_by_default(): void
    {
        $user = User::factory()->create();
        InterestLink::create(['title' => 'Zebra', 'url' => 'https://example.com', 'order' => 1, 'active' => true]);
        InterestLink::create(['title' => 'Apple', 'url' => 'https://example.com', 'order' => 2, 'active' => true]);

        $response = $this->actingAs($user)->get(route('admin.interest-links.index'));

        $response->assertOk();
        $response->assertSeeInOrder(['Zebra', 'Apple']);
    }
}
