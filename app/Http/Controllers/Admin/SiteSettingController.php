<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.site-settings.edit', [
            'setting' => SiteSetting::current(),
        ]);
    }

    public function update(UpdateSiteSettingRequest $request): RedirectResponse
    {
        SiteSetting::current()->update($request->validated());

        return redirect()->route('admin.site-settings.edit')->with('status', 'Configuración actualizada.');
    }
}
