<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientRequest;
use App\Http\Requests\Admin\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        return view('admin.clients.create', [
            'nextOrder' => Client::count() + 1,
        ]);
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'extra', 'order']);
        $data['extra'] = $data['extra'] ?? false;
        $data['order'] ??= 0;
        $data['img'] = Storage::url($request->file('image')->store('clients', 'public'));

        Client::create($data);

        return redirect()->route('admin.clients.index')->with('status', 'Cliente creado.');
    }

    public function edit(Client $client): View
    {
        return view('admin.clients.edit', ['client' => $client]);
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'extra', 'order']);
        $data['extra'] = $data['extra'] ?? false;
        $data['order'] ??= 0;

        if ($request->hasFile('image')) {
            $this->deleteStoredFile($client->img);
            $data['img'] = Storage::url($request->file('image')->store('clients', 'public'));
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')->with('status', 'Cliente actualizado.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->deleteStoredFile($client->img);
        $client->delete();

        return redirect()->route('admin.clients.index')->with('status', 'Cliente eliminado.');
    }

    private function deleteStoredFile(string $url): void
    {
        if (Str::startsWith($url, '/storage/')) {
            Storage::disk('public')->delete(Str::after($url, '/storage/'));
        }
    }
}
