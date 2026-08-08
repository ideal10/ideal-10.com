<?php

namespace App\Http\Controllers;

use App\Models\InterestLink;
use Illuminate\View\View;

class InterestLinkController extends Controller
{
    public function index(): View
    {
        $links = InterestLink::active()->ordered()->get();

        return view('pages.enlaces-de-interes', [
            'portales' => $links->reject(fn (InterestLink $link) => $link->isFile()),
            'archivos' => $links->filter(fn (InterestLink $link) => $link->isFile()),
        ]);
    }
}
