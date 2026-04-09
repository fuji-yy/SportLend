<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Return_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->with('tool')
            ->paginate(10);

        return view('peminjam.returns.index', compact('borrowings'));
    }

    public function create()
    {
        $borrowings = Borrowing::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->with('tool.category')
            ->get();

        return view('peminjam.returns.create', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        return view('peminjam.returns.show', compact('borrowing'));
    }

    public function store(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity_returned' => 'required|integer|min:1|max:' . $borrowing->quantity,
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($borrowing->status !== 'approved') {
            return back()->with('error', 'Peminjaman tidak dalam status approved.');
        }

        if ($borrowing->return) {
            return back()->with('error', 'Peminjaman ini sudah memiliki pengembalian.');
        }

        $return = Return_model::create([
            'borrowing_id' => $borrowing->id,
            'return_date' => now()->toDateString(),
            'quantity_returned' => $request->quantity_returned,
            'condition' => $request->condition,
            'notes' => $request->notes,
        ]);

        // Update tool availability
        $borrowing->tool->increment('available', $request->quantity_returned);

        // If all items returned, update borrowing status
        if ($request->quantity_returned === $borrowing->quantity) {
            $borrowing->update(['status' => 'returned']);
        }

        return redirect()->route('peminjam.returns.index')->with('success', 'Pengembalian berhasil dicatat.');
    }
}
