<?php

namespace App\Http\Controllers;

use App\Models\Componente;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ComponenteController extends Controller
{
    public function show(Componente $componente): View
    {
        $components = Componente::ordered()->get();
        $currentIndex = $components->search(fn (Componente $c) => $c->slug === $componente->slug) + 1;

        return view('pages.componentes.show', [
            'current' => $componente,
            'currentIndex' => $currentIndex,
            'components' => $components,
            'renderedContent' => Str::markdown((string) $componente->content, ['html_input' => 'allow']),
        ]);
    }
}
