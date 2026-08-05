<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreComponenteRequest;
use App\Http\Requests\Admin\UpdateComponenteRequest;
use App\Models\Componente;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ComponenteController extends Controller
{
    public function index(): View
    {
        return view('admin.componentes.index', [
            'componentes' => Componente::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.componentes.create');
    }

    public function store(StoreComponenteRequest $request): RedirectResponse
    {
        Componente::create($this->mapInput($request->validated()));

        return redirect()->route('admin.componentes.index')->with('status', 'Componente creado.');
    }

    public function edit(Componente $componente): View
    {
        return view('admin.componentes.edit', ['componente' => $componente]);
    }

    public function update(UpdateComponenteRequest $request, Componente $componente): RedirectResponse
    {
        $componente->update($this->mapInput($request->validated()));

        return redirect()->route('admin.componentes.index')->with('status', 'Componente actualizado.');
    }

    public function destroy(Componente $componente): RedirectResponse
    {
        $componente->delete();

        return redirect()->route('admin.componentes.index')->with('status', 'Componente eliminado.');
    }

    private function mapInput(array $data): array
    {
        $data['paths'] = array_values(array_filter(array_map('trim', explode("\n", $data['paths']))));
        $data['wide'] = $data['wide'] ?? false;
        $data['order'] ??= 0;

        return $data;
    }
}
