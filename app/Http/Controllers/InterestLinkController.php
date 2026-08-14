<?php

namespace App\Http\Controllers;

use App\Models\InterestLink;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function download(InterestLink $interestLink): StreamedResponse
    {
        abort_unless($interestLink->isFile(), 404);

        $path = Str::after($interestLink->url, '/storage/');

        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->download($path, $interestLink->original_name ?? basename($path));
    }
}
