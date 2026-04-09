<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Borrowing;
use App\Models\Return_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'tool'])->where('status', 'pending');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('tool', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->paginate(15);
        return view('petugas.borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        return view('petugas.borrowings.show', compact('borrowing'));
    }

    public function approve(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman tidak lagi dalam status pending.');
        }

        $isApproved = DB::transaction(function () use ($borrowing) {
            $tool = $borrowing->tool()->lockForUpdate()->first();

            if ($tool->available < $borrowing->quantity) {
                return false;
            }

            $borrowing->update(['status' => 'approved']);
            $tool->decrement('available', $borrowing->quantity);

            return true;
        });

        if (!$isApproved) {
            return back()->with('error', 'Stok alat tidak mencukupi untuk disetujui.');
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'approve',
            'model' => 'Borrowing',
            'model_id' => $borrowing->id,
            'description' => 'Menyetujui peminjaman #' . $borrowing->id,
        ]);

        return redirect()->route('petugas.borrowings.index')->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        $request->validate(['notes' => 'nullable|string']);

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman tidak lagi dalam status pending.');
        }

        $borrowing->update([
            'status' => 'rejected',
            'notes' => $request->notes,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'reject',
            'model' => 'Borrowing',
            'model_id' => $borrowing->id,
            'description' => 'Menolak peminjaman #' . $borrowing->id,
        ]);

        return redirect()->route('petugas.borrowings.index')->with('success', 'Peminjaman berhasil ditolak.');
    }
}
