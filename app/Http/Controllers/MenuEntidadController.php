<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\View\View;

class MenuEntidadController extends Controller
{
    public function show(Entity $entity): View
    {
        $entity->load(['client', 'links.client']);

        return view('pages.menu-entidad', [
            'entity' => $entity,
            'links' => $entity->links,
        ]);
    }
}
