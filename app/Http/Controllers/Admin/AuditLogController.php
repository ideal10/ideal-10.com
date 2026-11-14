<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Client;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Read-only browser for the audit log, with filters by person, section,
 * action, date range and free text.
 */
class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'user' => ['nullable', 'string', 'max:255'],
            'module' => ['nullable', 'string', 'in:'.implode(',', array_keys(AuditLog::MODULES))],
            'event' => ['nullable', 'string', 'in:'.implode(',', array_keys(AuditLog::EVENTS))],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $tz = config('app.display_timezone');

        $logs = AuditLog::query()
            ->when($filters['q'] ?? null, fn (Builder $query, string $q) => $query->where(fn (Builder $query) => $query
                ->where('auditable_label', 'like', "%{$q}%")
                ->orWhere('user_name', 'like', "%{$q}%")
                ->orWhere('ip_address', 'like', "%{$q}%")))
            ->when($filters['user'] ?? null, fn (Builder $query, string $user) => $query->where('user_name', $user))
            ->when($filters['module'] ?? null, fn (Builder $query, string $module) => $query->where('auditable_type', $module))
            ->when($filters['event'] ?? null, fn (Builder $query, string $event) => $query->where('event', $event))
            ->when($filters['from'] ?? null, fn (Builder $query, string $from) => $query->where('created_at', '>=', Carbon::parse($from, $tz)->startOfDay()->utc()))
            ->when($filters['to'] ?? null, fn (Builder $query, string $to) => $query->where('created_at', '<=', Carbon::parse($to, $tz)->endOfDay()->utc()))
            ->latest('created_at')
            ->latest('id')
            ->paginate(40)
            ->withQueryString();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'filters' => $filters,
            'users' => AuditLog::query()->whereNotNull('user_name')->distinct()->orderBy('user_name')->pluck('user_name'),
            'modules' => collect(AuditLog::MODULES)->sort(),
            'events' => AuditLog::EVENTS,
            // Resolve ids shown in change lists to readable names.
            'lookups' => [
                'client_id' => Client::pluck('name', 'id'),
                'entity_id' => Entity::pluck('name', 'id'),
            ],
        ]);
    }
}
