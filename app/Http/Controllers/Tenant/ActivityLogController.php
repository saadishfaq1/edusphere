<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-activity-log');

        $action = $request->query('action');

        $logs = ActivityLog::with('user')
            ->when($action, fn ($q) => $q->where('action', $action))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('tenant.activity-log.index', compact('logs', 'actions', 'action'));
    }
}