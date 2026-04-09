<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Return_model;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingBorrowings = Borrowing::where('status', 'pending')->count();
        $activeBorrowings = Borrowing::where('status', 'approved')->count();
        $recentReturns = Return_model::with(['borrowing.user', 'borrowing.tool'])
            ->latest()
            ->limit(5)
            ->get();

        return view('petugas.dashboard', compact(
            'pendingBorrowings',
            'activeBorrowings',
            'recentReturns'
        ));
    }
}
