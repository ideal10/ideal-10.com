<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\View\View;

class MenuEntidadController extends Controller
{
    public function show(Entity $entity): View
    {
        return view('layouts.standalone', [
            'name' => $entity->name,
            'links' => $entity->links,
        ]);
    }
}
