<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInterestLinkRequest;
use App\Http\Requests\Admin\UpdateInterestLinkRequest;
use App\Models\InterestLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InterestLinkController extends Controller
{
    public function index(): View
    {
        return view('admin.interest-links.index', [
            'links' => InterestLink::ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.interest-links.create', [
            'nextOrder' => InterestLink::count() + 1,
        ]);
    }

    public function store(StoreInterestLinkRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['title', 'description', 'icon', 'order', 'active']);
        $data['order'] ??= 0;
        $data['active'] = $request->boolean('active', true);

        if ($request->input('link_type') === 'file') {
            $data['url'] = Storage::url($request->file('file')->store('interest-links', 'public'));
            $data['original_name'] = $request->file('file')->getClientOriginalName();
        } else {
            $data['url'] = $request->input('url');
        }

        InterestLink::create($data);

        return redirect()->route('admin.interest-links.index')->with('status', 'Enlace creado.');
    }

    public function edit(InterestLink $interestLink): View
    {
        return view('admin.interest-links.edit', ['link' => $interestLink]);
    }

    public function update(UpdateInterestLinkRequest $request, InterestLink $interestLink): RedirectResponse
    {
        $data = $request->safe()->only(['title', 'description', 'icon', 'order', 'active']);
        $data['order'] ??= 0;
        $data['active'] = $request->boolean('active', true);

        if ($request->input('link_type') === 'file') {
            if ($request->hasFile('file')) {
                $this->deleteStoredFile($interestLink->url);
                $data['url'] = Storage::url($request->file('file')->store('interest-links', 'public'));
                $data['original_name'] = $request->file('file')->getClientOriginalName();
            }
            // No new file uploaded: keep the existing url/original_name as-is.
        } else {
            $this->deleteStoredFile($interestLink->url);
            $data['url'] = $request->input('url');
            $data['original_name'] = null;
        }

        $interestLink->update($data);

        return redirect()->route('admin.interest-links.index')->with('status', 'Enlace actualizado.');
    }

    public function destroy(InterestLink $interestLink): RedirectResponse
    {
        $this->deleteStoredFile($interestLink->url);
        $interestLink->delete();

        return redirect()->route('admin.interest-links.index')->with('status', 'Enlace eliminado.');
    }

    public function toggle(InterestLink $interestLink): RedirectResponse
    {
        $interestLink->update(['active' => ! $interestLink->active]);

        return redirect()->route('admin.interest-links.index');
    }

    public function reorder(Request $request): RedirectResponse
    {
        foreach ($request->input('ids', []) as $order => $id) {
            InterestLink::whereKey($id)->update(['order' => $order + 1]);
        }

        return redirect()->route('admin.interest-links.index');
    }

    public function sortByTitle(Request $request): RedirectResponse
    {
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';

        InterestLink::query()
            ->orderByRaw("LOWER(title) {$direction}")
            ->orderBy('id')
            ->get()
            ->values()
            ->each(fn (InterestLink $link, int $index) => $link->update(['order' => $index + 1]));

        return redirect()->route('admin.interest-links.index')->with('status', 'Enlaces ordenados alfabéticamente.');
    }

    private function deleteStoredFile(string $url): void
    {
        if (Str::startsWith($url, '/storage/')) {
            Storage::disk('public')->delete(Str::after($url, '/storage/'));
        }
    }
}
