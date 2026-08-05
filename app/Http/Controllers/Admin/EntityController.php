<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEntityRequest;
use App\Http\Requests\Admin\UpdateEntityRequest;
use App\Models\Entity;
use App\Models\EntityLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EntityController extends Controller
{
    public function index(): View
    {
        return view('admin.entities.index', [
            'entities' => Entity::withCount('links')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.entities.create');
    }

    public function store(StoreEntityRequest $request): RedirectResponse
    {
        $entity = Entity::create($request->validated());

        return redirect()->route('admin.entities.edit', $entity)->with('status', 'Entidad creada. Ahora puedes añadir sus enlaces.');
    }

    public function edit(Entity $entity): View
    {
        return view('admin.entities.edit', [
            'entity' => $entity,
            'links' => $entity->links,
        ]);
    }

    public function update(UpdateEntityRequest $request, Entity $entity): RedirectResponse
    {
        $entity->update($request->safe()->only(['slug', 'name']));

        foreach ($request->validated('links', []) as $row) {
            $id = $row['id'] ?? null;
            $remove = (bool) ($row['remove'] ?? false);
            $name = trim($row['name'] ?? '');
            $href = trim($row['href'] ?? '');

            if ($id && $remove) {
                EntityLink::whereKey($id)->where('entity_id', $entity->id)->delete();

                continue;
            }

            if ($id && $name !== '' && $href !== '') {
                EntityLink::whereKey($id)->where('entity_id', $entity->id)->update([
                    'name' => $name,
                    'href' => $href,
                    'order' => $row['order'] ?? 0,
                ]);

                continue;
            }

            if (! $id && $name !== '' && $href !== '') {
                $entity->links()->create([
                    'name' => $name,
                    'href' => $href,
                    'order' => $row['order'] ?? 0,
                ]);
            }
        }

        return redirect()->route('admin.entities.edit', $entity)->with('status', 'Entidad actualizada.');
    }

    public function destroy(Entity $entity): RedirectResponse
    {
        $entity->delete();

        return redirect()->route('admin.entities.index')->with('status', 'Entidad eliminada.');
    }
}
