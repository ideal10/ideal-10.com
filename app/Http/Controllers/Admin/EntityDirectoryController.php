<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use Illuminate\View\View;

/**
 * Read-only directory where staff browse every entity and jump straight
 * into any of its sub-entities. Managing them lives in EntityController.
 */
class EntityDirectoryController extends Controller
{
    public function index(): View
    {
        return view('admin.entity-directory.index', [
            'entities' => Entity::with(['client', 'links.client'])->orderBy('name')->get(),
        ]);
    }
}
