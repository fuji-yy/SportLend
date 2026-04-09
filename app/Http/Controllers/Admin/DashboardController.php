<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Borrowing;
use App\Models\Tool;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTools = Tool::sum('quantity');
        $activeBorrowings = Borrowing::where('status', 'approved')->count();
        $pendingBorrowings = Borrowing::where('status', 'pending')->count();
        
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTools',
            'activeBorrowings',
            'pendingBorrowings',
            'recentActivities'
        ));
    }
}
