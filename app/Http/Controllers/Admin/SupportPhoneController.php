<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSupportPhoneRequest;
use App\Http\Requests\Admin\UpdateSupportPhoneRequest;
use App\Models\SupportPhone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportPhoneController extends Controller
{
    public function index(): View
    {
        return view('admin.support-phones.index', [
            'phones' => SupportPhone::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.support-phones.create', [
            'nextOrder' => SupportPhone::count() + 1,
        ]);
    }

    public function store(StoreSupportPhoneRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['number', 'type', 'order']);
        $data['order'] ??= 0;
        $data['active'] = $request->boolean('active', true);

        SupportPhone::create($data);

        return redirect()->route('admin.support-phones.index')->with('status', 'Número creado.');
    }

    public function edit(SupportPhone $supportPhone): View
    {
        return view('admin.support-phones.edit', ['phone' => $supportPhone]);
    }

    public function update(UpdateSupportPhoneRequest $request, SupportPhone $supportPhone): RedirectResponse
    {
        $data = $request->safe()->only(['number', 'type', 'order']);
        $data['order'] ??= 0;
        $data['active'] = $request->boolean('active', true);

        $supportPhone->update($data);

        return redirect()->route('admin.support-phones.index')->with('status', 'Número actualizado.');
    }

    public function destroy(SupportPhone $supportPhone): RedirectResponse
    {
        $supportPhone->delete();

        return redirect()->route('admin.support-phones.index')->with('status', 'Número eliminado.');
    }

    public function toggle(SupportPhone $supportPhone): RedirectResponse
    {
        $supportPhone->update(['active' => ! $supportPhone->active]);

        return redirect()->route('admin.support-phones.index');
    }

    public function reorder(Request $request): RedirectResponse
    {
        foreach ($request->input('ids', []) as $order => $id) {
            SupportPhone::whereKey($id)->update(['order' => $order + 1]);
        }

        return redirect()->route('admin.support-phones.index');
    }
}
