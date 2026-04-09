<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Return_model;

class ReportController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'tool'])->get();
        $returns = Return_model::with(['borrowing.user', 'borrowing.tool'])->get();
        return view('petugas.reports.index', compact('borrowings', 'returns'));
    }

    public function borrowingReport()
    {
        $borrowings = Borrowing::with(['user', 'tool'])->get();
        return view('petugas.reports.borrowing', compact('borrowings'));
    }

    public function returnReport()
    {
        $returns = Return_model::with(['borrowing.user', 'borrowing.tool'])->get();
        return view('petugas.reports.return', compact('returns'));
    }
}
