<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->get('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->get('to_date'));
        }

        $logs = $query->latest()->paginate(50);
        $users = \App\Models\User::orderBy('name')->get();

        return view('admin.logs.index', compact('logs', 'users'));
    }

    public function show(ActivityLog $log)
    {
        return view('admin.logs.show', compact('log'));
    }
}
