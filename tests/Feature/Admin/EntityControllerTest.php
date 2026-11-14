<?php

namespace Tests\Feature\Admin;

use App\Models\Client;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EntityControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.entities.index'))->assertRedirect(route('login'));
    }

    public function test_index_and_forms_render(): void
    {
        $user = User::factory()->create();
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Alcaldía de Cubarral']);
        $entity->links()->create(['name' => 'Concejo', 'href' => 'https://cubarralc.ideal-10.com', 'order' => 1]);

        $this->actingAs($user)->get(route('admin.entities.index'))->assertOk()->assertSee('Alcaldía de Cubarral');
        $this->actingAs($user)->get(route('admin.entities.create'))->assertOk()->assertSee('Crear entidad');
        $this->actingAs($user)->get(route('admin.entities.edit', $entity))->assertOk()->assertSee('Concejo');
    }

    public function test_admin_can_create_an_entity_with_logos_and_sub_entities(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.entities.store'), [
            'name' => 'Alcaldía de Cubarral',
            'slug' => 'cubarral',
            'logo' => UploadedFile::fake()->image('escudo.png'),
            'links' => [
                ['name' => 'Alcaldía', 'href' => 'https://cubarral.ideal-10.com', 'logo' => UploadedFile::fake()->image('alcaldia.png')],
                ['name' => 'Concejo', 'href' => 'https://cubarralc.ideal-10.com'],
            ],
        ]);

        $entity = Entity::sole();
        $response->assertRedirect(route('admin.entities.edit', $entity));

        Storage::disk('public')->assertExists($entity->logo);
        $this->assertSame(['Alcaldía', 'Concejo'], $entity->links->pluck('name')->all());
        Storage::disk('public')->assertExists($entity->links[0]->logo);
        $this->assertNull($entity->links[1]->logo);
    }

    public function test_update_reorders_removes_and_replaces_logos(): void
    {
        $user = User::factory()->create();
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral', 'logo' => UploadedFile::fake()->image('old.png')->store('entities', 'public')]);
        $concejo = $entity->links()->create(['name' => 'Concejo', 'href' => 'https://a.test', 'order' => 1, 'logo' => UploadedFile::fake()->image('c.png')->store('entities/links', 'public')]);
        $personeria = $entity->links()->create(['name' => 'Personería', 'href' => 'https://b.test', 'order' => 2]);
        $oldEntityLogo = $entity->logo;
        $oldConcejoLogo = $concejo->logo;

        $this->actingAs($user)->put(route('admin.entities.update', $entity), [
            'name' => 'Alcaldía de Cubarral',
            'slug' => 'cubarral',
            'logo' => UploadedFile::fake()->image('new.png'),
            'links' => [
                ['id' => $personeria->id, 'name' => 'Personería', 'href' => 'https://b.test'],
                ['name' => 'Servicios públicos', 'href' => 'https://c.test'],
            ],
        ])->assertRedirect(route('admin.entities.edit', $entity));

        $entity->refresh();
        Storage::disk('public')->assertMissing($oldEntityLogo);
        Storage::disk('public')->assertExists($entity->logo);
        Storage::disk('public')->assertMissing($oldConcejoLogo);
        $this->assertSame(['Personería', 'Servicios públicos'], $entity->links->pluck('name')->all());
        $this->assertDatabaseMissing('entity_links', ['id' => $concejo->id]);
    }

    public function test_update_can_remove_a_logo(): void
    {
        $user = User::factory()->create();
        $logo = UploadedFile::fake()->image('old.png')->store('entities', 'public');
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral', 'logo' => $logo]);

        $this->actingAs($user)->put(route('admin.entities.update', $entity), [
            'name' => 'Cubarral',
            'slug' => 'cubarral',
            'remove_logo' => 1,
        ]);

        $this->assertNull($entity->refresh()->logo);
        Storage::disk('public')->assertMissing($logo);
    }

    public function test_links_from_another_entity_cannot_be_hijacked(): void
    {
        $user = User::factory()->create();
        $other = Entity::create(['slug' => 'otra', 'name' => 'Otra']);
        $foreign = $other->links()->create(['name' => 'Concejo', 'href' => 'https://a.test', 'order' => 1]);
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral']);

        $this->actingAs($user)->put(route('admin.entities.update', $entity), [
            'name' => 'Cubarral',
            'slug' => 'cubarral',
            'links' => [['id' => $foreign->id, 'name' => 'Robado', 'href' => 'https://x.test']],
        ]);

        $this->assertSame('Concejo', $foreign->refresh()->name);
        $this->assertSame($other->id, $foreign->entity_id);
    }

    public function test_store_rejects_invalid_logo_and_link_url(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.entities.store'), [
            'name' => 'Cubarral',
            'slug' => 'cubarral',
            'logo' => UploadedFile::fake()->create('doc.pdf', 10, 'application/pdf'),
            'links' => [['name' => 'Concejo', 'href' => 'cubarral ideal 10']],
        ])->assertSessionHasErrors(['logo', 'links.0.href']);

        $this->assertDatabaseCount('entities', 0);
    }

    public function test_destroy_deletes_entity_links_and_logo_files(): void
    {
        $user = User::factory()->create();
        $entityLogo = UploadedFile::fake()->image('e.png')->store('entities', 'public');
        $linkLogo = UploadedFile::fake()->image('l.png')->store('entities/links', 'public');
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral', 'logo' => $entityLogo]);
        $entity->links()->create(['name' => 'Concejo', 'href' => 'https://a.test', 'order' => 1, 'logo' => $linkLogo]);

        $this->actingAs($user)->delete(route('admin.entities.destroy', $entity))->assertRedirect(route('admin.entities.index'));

        $this->assertDatabaseCount('entities', 0);
        $this->assertDatabaseCount('entity_links', 0);
        Storage::disk('public')->assertMissing($entityLogo);
        Storage::disk('public')->assertMissing($linkLogo);
    }

    public function test_public_menu_shows_entity_and_sub_entities(): void
    {
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Alcaldía de Cubarral']);
        $entity->links()->create(['name' => 'Personería', 'href' => 'https://cubarralp.ideal-10.com', 'order' => 1]);

        $this->get(route('menu-entidades.show', $entity))
            ->assertOk()
            ->assertSee('Alcaldía de Cubarral')
            ->assertSee('Personería')
            ->assertSee('cubarralp.ideal-10.com');
    }

    public function test_entity_and_sub_entities_can_reuse_client_logos(): void
    {
        $user = User::factory()->create();
        $alcaldia = Client::create(['name' => 'Alcaldia de Cubarral', 'img' => '/storage/clients/cubarral.png', 'order' => 1]);
        $concejo = Client::create(['name' => 'Concejo de Cubarral', 'img' => '/storage/clients/concejo-cubarral.png', 'order' => 2]);

        $this->actingAs($user)->post(route('admin.entities.store'), [
            'name' => 'Alcaldía de Cubarral',
            'slug' => 'cubarral',
            'client_id' => $alcaldia->id,
            'links' => [
                ['name' => 'Concejo', 'href' => 'https://cubarralc.ideal-10.com', 'client_id' => $concejo->id],
            ],
        ])->assertSessionHasNoErrors();

        $entity = Entity::sole();
        $this->assertSame('/storage/clients/cubarral.png', $entity->logo_url);
        $this->assertNull($entity->logo);
        $this->assertSame('/storage/clients/concejo-cubarral.png', $entity->links[0]->logo_url);

        $this->get(route('menu-entidades.show', $entity))
            ->assertSee('/storage/clients/cubarral.png', false)
            ->assertSee('/storage/clients/concejo-cubarral.png', false);
    }

    public function test_choosing_a_client_logo_deletes_the_previous_upload(): void
    {
        $user = User::factory()->create();
        $client = Client::create(['name' => 'Alcaldia de Cubarral', 'img' => '/storage/clients/cubarral.png', 'order' => 1]);
        $upload = UploadedFile::fake()->image('old.png')->store('entities', 'public');
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral', 'logo' => $upload]);

        $this->actingAs($user)->put(route('admin.entities.update', $entity), [
            'name' => 'Cubarral',
            'slug' => 'cubarral',
            'client_id' => $client->id,
        ])->assertSessionHasNoErrors();

        $entity->refresh();
        $this->assertSame($client->id, $entity->client_id);
        $this->assertNull($entity->logo);
        Storage::disk('public')->assertMissing($upload);
    }

    public function test_uploading_an_image_replaces_a_client_logo(): void
    {
        $user = User::factory()->create();
        $client = Client::create(['name' => 'Alcaldia de Cubarral', 'img' => '/storage/clients/cubarral.png', 'order' => 1]);
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral', 'client_id' => $client->id]);

        $this->actingAs($user)->put(route('admin.entities.update', $entity), [
            'name' => 'Cubarral',
            'slug' => 'cubarral',
            'client_id' => $client->id,
            'logo' => UploadedFile::fake()->image('nuevo.png'),
        ]);

        $entity->refresh();
        $this->assertNull($entity->client_id);
        Storage::disk('public')->assertExists($entity->logo);
    }

    public function test_deleting_a_client_leaves_the_entity_without_logo(): void
    {
        $client = Client::create(['name' => 'Alcaldia de Cubarral', 'img' => '/storage/clients/cubarral.png', 'order' => 1]);
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral', 'client_id' => $client->id]);

        $client->delete();

        $this->assertNull($entity->refresh()->client_id);
        $this->assertNull($entity->logo_url);
    }

    public function test_links_without_scheme_get_https_added(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('admin.entities.store'), [
            'name' => 'Cubarral',
            'slug' => 'cubarral',
            'links' => [
                ['name' => 'Alcaldía', 'href' => 'cubarral.ideal-10.com'],
                ['name' => 'Concejo', 'href' => 'http://cubarralc.ideal-10.com/'],
            ],
        ])->assertSessionHasNoErrors();

        $this->assertSame(
            ['https://cubarral.ideal-10.com', 'http://cubarralc.ideal-10.com/'],
            Entity::sole()->links->pluck('href')->all(),
        );
    }

    public function test_public_menu_links_open_in_a_new_tab(): void
    {
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral']);
        $entity->links()->create(['name' => 'Concejo', 'href' => 'https://cubarralc.ideal-10.com', 'order' => 1]);

        $this->get(route('menu-entidades.show', $entity))
            ->assertSee('href="https://cubarralc.ideal-10.com"'."\n".'                            target="_blank"', false);
    }

    public function test_directory_lists_entities_and_their_sub_entities(): void
    {
        $user = User::factory()->create();
        $entity = Entity::create(['slug' => 'cubarral', 'name' => 'Alcaldía de Cubarral']);
        $entity->links()->create(['name' => 'Personería', 'href' => 'https://cubarralp.ideal-10.com', 'order' => 1]);
        Entity::create(['slug' => 'vacia', 'name' => 'Entidad sin dependencias']);

        $this->get(route('admin.entity-directory.index'))->assertRedirect(route('login'));

        $this->actingAs($user)->get(route('admin.entity-directory.index'))
            ->assertOk()
            ->assertSeeInOrder(['Alcaldía de Cubarral', 'Entidad sin dependencias'])
            ->assertSee('Personería')
            ->assertSee('cubarralp.ideal-10.com');
    }

    public function test_dashboard_still_renders_with_the_directory_item(): void
    {
        $user = User::factory()->create();
        Entity::create(['slug' => 'cubarral', 'name' => 'Cubarral']);

        $this->actingAs($user)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Directorio de entidades');
    }

    public function test_slug_is_generated_from_the_name_and_kept_on_rename(): void
    {
        $user = User::factory()->create();
        Entity::create(['name' => 'Alcaldía de Cubarral']);

        $this->actingAs($user)->post(route('admin.entities.store'), ['name' => 'Alcaldía de Cubarral'])->assertSessionHasNoErrors();

        $this->assertSame(['alcaldia-de-cubarral', 'alcaldia-de-cubarral-2'], Entity::orderBy('id')->pluck('slug')->all());

        $entity = Entity::firstWhere('slug', 'alcaldia-de-cubarral');
        $this->actingAs($user)->put(route('admin.entities.update', $entity), ['name' => 'Municipio de Cubarral', 'slug' => 'otro'])
            ->assertRedirect(route('admin.entities.edit', 'alcaldia-de-cubarral'));

        $this->assertSame('alcaldia-de-cubarral', $entity->refresh()->slug);
        $this->assertSame('Municipio de Cubarral', $entity->name);
    }
}
