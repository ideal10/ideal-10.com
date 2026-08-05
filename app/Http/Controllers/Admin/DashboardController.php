<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminNavigation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $counts = collect(AdminNavigation::groups())
            ->flatMap(fn (array $group) => $group['items'])
            ->mapWithKeys(fn (array $item) => [$item['index'] => $item['model']::count()]);

        return view('admin.dashboard', [
            'groups' => AdminNavigation::groups(),
            'standalone' => AdminNavigation::standalone(),
            'counts' => $counts,
        ]);
    }
}
