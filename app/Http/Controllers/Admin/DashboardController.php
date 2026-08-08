<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterestLink;
use App\Models\NavItem;
use App\Support\AdminNavigation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Model attribute used as the human-readable title in the activity feed. */
    private const TITLE_FIELDS = [
        InterestLink::class => 'title',
        NavItem::class => 'label',
    ];

    public function index(): View
    {
        $groups = AdminNavigation::groups();

        $items = collect($groups)->flatMap(fn (array $group) => $group['items']);

        $counts = $items->mapWithKeys(fn (array $item) => [$item['index'] => $item['model']::count()]);

        $recent = $items
            ->flatMap(function (array $item) {
                $titleField = self::TITLE_FIELDS[$item['model']] ?? 'name';

                return $item['model']::query()
                    ->latest('updated_at')
                    ->take(5)
                    ->get()
                    ->map(fn ($model) => [
                        'title' => $model->{$titleField},
                        'label' => $item['label'],
                        'icon' => $item['icon'],
                        'updated_at' => $model->updated_at,
                        'edit_route' => str_replace('.index', '.edit', $item['index']),
                        'model' => $model,
                    ]);
            })
            ->sortByDesc('updated_at')
            ->take(8)
            ->values();

        return view('admin.dashboard', [
            'groups' => $groups,
            'standalone' => AdminNavigation::standalone(),
            'counts' => $counts,
            'totalItems' => $counts->sum(),
            'recent' => $recent,
            'inactiveLinks' => InterestLink::query()->where('active', false)->count(),
        ]);
    }
}
