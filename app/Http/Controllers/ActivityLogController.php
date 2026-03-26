<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view-audit-logs');

        $query = ActivityLog::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('subject_type', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs    = $query->paginate(20)->withQueryString();
        $users   = User::orderBy('name')->get();
        $actions = ActivityLog::ACTIONS;

        return view('activity-logs.index', compact('logs', 'users', 'actions'));
    }

    public function show(ActivityLog $activityLog)
    {
        $this->authorize('view-audit-logs');
        $activityLog->load('user');
        return view('activity-logs.show', compact('activityLog'));
    }
}   