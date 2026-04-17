<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::where('user_id', Auth::id())
            ->with(['tool', 'fine'])
            ->latest()
            ->paginate(10);

        return view('peminjam.borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        $borrowing->load(['tool.category', 'fine']);

        return view('peminjam.borrowings.show', compact('borrowing'));
    }

    public function create()
    {
        $tools = Tool::with('category')->where('available', '>', 0)->get();
        return view('peminjam.borrowings.create', compact('tools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date|after_or_equal:today',
            'due_date' => 'required|date|after:borrow_date',
            'purpose' => 'nullable|string|max:500',
        ]);

        $tool = Tool::findOrFail($request->tool_id);

        if ($request->quantity > $tool->available) {
            return back()->withErrors(['quantity' => 'Jumlah alat tidak tersedia.']);
        }

        $borrowing = Borrowing::create([
            'user_id' => Auth::id(),
            'tool_id' => $request->tool_id,
            'quantity' => $request->quantity,
            'borrow_date' => $request->borrow_date,
            'due_date' => $request->due_date,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Borrowing',
            'model_id' => $borrowing->id,
            'description' => 'Membuat permohonan peminjaman #' . $borrowing->id,
        ]);

        return redirect()->route('peminjam.borrowings.show', $borrowing)->with('success', 'Permohonan peminjaman berhasil dibuat. Tunggu persetujuan dari petugas.');
    }

    public function cancel(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Hanya peminjaman dengan status pending yang bisa dibatalkan.');
        }

        $borrowing->update(['status' => 'rejected']);

        return redirect()->route('peminjam.borrowings.index')->with('success', 'Permohonan peminjaman berhasil dibatalkan.');
    }
}
