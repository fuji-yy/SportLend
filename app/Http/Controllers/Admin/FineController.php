<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Fine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $query = Fine::with(['borrowing.user', 'borrowing.tool']);

        if ($request->filled('user_id')) {
            $query->whereHas('borrowing', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $fines = $query->latest()->paginate(15)->withQueryString();
        $users = User::whereIn('role', ['peminjam', 'admin', 'petugas'])->orderBy('name')->get();

        return view('admin.fines.index', compact('fines', 'users'));
    }

    public function show(Fine $fine)
    {
        $fine->load(['borrowing.user', 'borrowing.tool']);

        return view('admin.fines.show', compact('fine'));
    }

    public function markPaid(Request $request, Fine $fine)
    {
        $request->validate([
            'notes_admin' => 'nullable|string|max:1000',
        ]);

        $oldData = $fine->toArray();

        $fine->update([
            'status' => 'paid',
            'paid_at' => now(),
            'notes_admin' => $request->filled('notes_admin') ? $request->notes_admin : $fine->notes_admin,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'Fine',
            'model_id' => $fine->id,
            'description' => 'Menandai denda sebagai lunas untuk peminjaman #' . $fine->borrowing_id,
            'old_data' => $oldData,
            'new_data' => $fine->fresh()->toArray(),
        ]);

        return redirect()->route('admin.fines.show', $fine)->with('success', 'Denda berhasil ditandai lunas.');
    }

    public function waive(Request $request, Fine $fine)
    {
        $request->validate([
            'notes_admin' => 'required|string|max:1000',
        ]);

        $oldData = $fine->toArray();

        $fine->update([
            'status' => 'waived',
            'paid_at' => null,
            'notes_admin' => $request->notes_admin,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'Fine',
            'model_id' => $fine->id,
            'description' => 'Menandai denda sebagai waived untuk peminjaman #' . $fine->borrowing_id,
            'old_data' => $oldData,
            'new_data' => $fine->fresh()->toArray(),
        ]);

        return redirect()->route('admin.fines.show', $fine)->with('success', 'Denda berhasil di-waive.');
    }
}
