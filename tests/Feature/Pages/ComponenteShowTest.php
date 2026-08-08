<?php

namespace Tests\Feature\Pages;

use App\Models\Componente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComponenteShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_raw_html_event_handlers_in_content_are_not_rendered(): void
    {
        $componente = Componente::create([
            'slug' => 'financiero',
            'name' => 'Financiero',
            'body' => 'Teaser',
            'paths' => [],
            'content' => "# Título\n\n<img src=x onerror=alert(1)>",
        ]);

        $response = $this->get(route('componentes.show', $componente));

        $response->assertOk();
        $response->assertDontSee('onerror=alert(1)', false);
    }

    public function test_javascript_scheme_markdown_links_are_not_rendered(): void
    {
        $componente = Componente::create([
            'slug' => 'financiero',
            'name' => 'Financiero',
            'body' => 'Teaser',
            'paths' => [],
            'content' => '[click me](javascript:alert(1))',
        ]);

        $response = $this->get(route('componentes.show', $componente));

        $response->assertOk();
        $response->assertDontSee('javascript:alert(1)', false);
    }
}
