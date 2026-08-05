<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNavItemRequest;
use App\Http\Requests\Admin\UpdateNavItemRequest;
use App\Models\NavItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NavItemController extends Controller
{
    public function index(): View
    {
        return view('admin.nav-items.index', [
            'navItems' => NavItem::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.nav-items.create');
    }

    public function store(StoreNavItemRequest $request): RedirectResponse
    {
        NavItem::create($this->mapInput($request->validated()));

        return redirect()->route('admin.nav-items.index')->with('status', 'Enlace de navegación creado.');
    }

    public function edit(NavItem $navItem): View
    {
        return view('admin.nav-items.edit', ['navItem' => $navItem]);
    }

    public function update(UpdateNavItemRequest $request, NavItem $navItem): RedirectResponse
    {
        $navItem->update($this->mapInput($request->validated()));

        return redirect()->route('admin.nav-items.index')->with('status', 'Enlace de navegación actualizado.');
    }

    public function destroy(NavItem $navItem): RedirectResponse
    {
        $navItem->delete();

        return redirect()->route('admin.nav-items.index')->with('status', 'Enlace de navegación eliminado.');
    }

    private function mapInput(array $data): array
    {
        $data['match'] = filled($data['match'] ?? null)
            ? array_values(array_filter(array_map('trim', explode(',', $data['match']))))
            : null;

        $data['order'] ??= 0;

        return $data;
    }
}
