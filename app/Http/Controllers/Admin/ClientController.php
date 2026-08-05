<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Http\Requests\Admin\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        return view('admin.clients.index', [
            'clients' => Client::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        Client::create($this->mapInput($request->validated()));

        return redirect()->route('admin.clients.index')->with('status', 'Cliente creado.');
    }

    public function edit(Client $client): View
    {
        return view('admin.clients.edit', ['client' => $client]);
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($this->mapInput($request->validated()));

        return redirect()->route('admin.clients.index')->with('status', 'Cliente actualizado.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('admin.clients.index')->with('status', 'Cliente eliminado.');
    }

    private function mapInput(array $data): array
    {
        $data['extra'] = $data['extra'] ?? false;
        $data['order'] ??= 0;

        return $data;
    }
}
