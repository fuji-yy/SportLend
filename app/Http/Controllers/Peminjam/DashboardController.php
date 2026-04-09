<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalBorrowings = $user->borrowings()->count();
        $activeBorrowings = $user->borrowings()->where('status', 'approved')->count();
        $pendingBorrowings = $user->borrowings()->where('status', 'pending')->count();
        
        $recentBorrowings = $user->borrowings()
            ->with('tool')
            ->latest()
            ->limit(5)
            ->get();

        return view('peminjam.dashboard', compact(
            'totalBorrowings',
            'activeBorrowings',
            'pendingBorrowings',
            'recentBorrowings'
        ));
    }
}
