<?php

namespace Tests\Feature\Admin;

use App\Models\AuditLog;
use App\Models\Entity;
use App\Models\InterestLink;
use App\Models\SupportPhone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_changes_are_recorded_with_who_and_what(): void
    {
        $user = User::factory()->create(['name' => 'Omar']);
        $this->actingAs($user);

        $entity = Entity::create(['name' => 'Alcaldía de Cubarral']);
        $entity->update(['name' => 'Municipio de Cubarral']);
        $entity->delete();

        $logs = AuditLog::where('auditable_type', Entity::class)->orderBy('id')->get();

        $this->assertSame(['created', 'updated', 'deleted'], $logs->pluck('event')->all());
        $this->assertTrue($logs->every(fn ($log) => $log->user_id === $user->id && $log->user_name === 'Omar'));
        $this->assertSame(['old' => 'Alcaldía de Cubarral', 'new' => 'Municipio de Cubarral'], $logs[1]->changes['name']);
        $this->assertSame(['name'], array_keys($logs[1]->changes));
        $this->assertSame('Municipio de Cubarral', $logs[2]->auditable_label);
    }

    public function test_updates_that_change_nothing_are_not_recorded(): void
    {
        $entity = Entity::create(['name' => 'Cubarral']);
        $entity->update(['name' => 'Cubarral']);
        $entity->touch();

        $this->assertSame(1, AuditLog::count());
    }

    public function test_passwords_are_never_stored(): void
    {
        $user = User::factory()->create(['password' => 'secreto-original']);
        $user->update(['password' => 'secreto-nuevo']);

        $log = AuditLog::where('event', 'updated')->sole();

        $this->assertSame(['old' => '••••••', 'new' => '••••••'], $log->changes['password']);
        $this->assertStringNotContainsString('secreto', json_encode(AuditLog::all()->pluck('changes')));
    }

    public function test_sign_in_sign_out_and_failed_attempts_are_recorded(): void
    {
        $user = User::factory()->create(['email' => 'ana@ideal10.com', 'password' => 'clave-correcta']);

        $this->post(route('login'), ['login' => 'ana@ideal10.com', 'password' => 'mala']);
        $this->post(route('login'), ['login' => 'ana@ideal10.com', 'password' => 'clave-correcta']);
        $this->post(route('logout'));

        $this->assertSame(['login_failed', 'login', 'logout'], AuditLog::where('event', '!=', 'created')->orderBy('id')->pluck('event')->all());
        $this->assertSame('ana@ideal10.com', AuditLog::firstWhere('event', 'login_failed')->auditable_label);
        $this->assertSame($user->id, AuditLog::firstWhere('event', 'login')->user_id);
    }

    public function test_reordering_is_recorded_once(): void
    {
        $user = User::factory()->create();
        $a = SupportPhone::create(['number' => '3202497418', 'type' => 'whatsapp', 'order' => 1, 'active' => true]);
        $b = SupportPhone::create(['number' => '3202497419', 'type' => 'whatsapp', 'order' => 2, 'active' => true]);
        InterestLink::create(['title' => 'B', 'url' => 'https://b.test', 'order' => 1, 'active' => true]);
        InterestLink::create(['title' => 'A', 'url' => 'https://a.test', 'order' => 2, 'active' => true]);
        AuditLog::query()->delete();

        $this->actingAs($user)->post(route('admin.support-phones.reorder'), ['ids' => [$b->id, $a->id]]);
        $this->actingAs($user)->post(route('admin.interest-links.sort-by-title'), ['direction' => 'asc']);

        $this->assertSame(2, AuditLog::count());
        $this->assertSame(2, AuditLog::where('event', 'reordered')->count());
        $this->assertSame(1, InterestLink::firstWhere('title', 'A')->order);
    }

    public function test_audit_page_requires_login_and_filters(): void
    {
        $this->get(route('admin.audit-logs.index'))->assertRedirect(route('login'));

        $user = User::factory()->create(['name' => 'Omar']);
        $this->actingAs($user);
        Entity::create(['name' => 'Alcaldía de Cubarral']);
        SupportPhone::create(['number' => '3202497418', 'type' => 'whatsapp', 'order' => 1, 'active' => true]);

        $this->get(route('admin.audit-logs.index'))
            ->assertOk()
            ->assertSee('Alcaldía de Cubarral')
            ->assertSee('3202497418');

        $this->get(route('admin.audit-logs.index', ['module' => Entity::class]))
            ->assertOk()
            ->assertSee('Alcaldía de Cubarral')
            ->assertDontSee('3202497418');

        $this->get(route('admin.audit-logs.index', ['event' => 'nope']))->assertSessionHasErrors('event');
    }
}
