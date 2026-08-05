<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Componente;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'services' => Service::ordered()->get(),
            'clients' => Client::home()->ordered()->get(),
            'components' => Componente::ordered()->get(),
        ]);
    }

    public function nosotros(): View
    {
        return view('pages.nosotros', [
            'tickerClients' => Client::ordered()->get(),
        ]);
    }

    public function contacto(): View
    {
        return view('pages.contacto', [
            'site' => SiteSetting::current(),
        ]);
    }

    public function ideal10(): View
    {
        return view('pages.ideal-10', [
            'components' => Componente::ordered()->get(),
        ]);
    }
}
