<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Return_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;
use App\Services\FineCalculatorService;
use Illuminate\Support\Facades\DB;

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

        $borrowing->load(['tool', 'fine']);

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
            return back()->with('error', 'Peminjaman tidak dalam status disetujui.');
        }

        if ($borrowing->return) {
            return back()->with('error', 'Peminjaman ini sudah memiliki pengembalian.');
        }

        $result = DB::transaction(function () use ($request, $borrowing) {
            $borrowing = Borrowing::with(['return', 'tool'])->lockForUpdate()->findOrFail($borrowing->id);
            $tool = $borrowing->tool()->lockForUpdate()->first();

            $return = Return_model::create([
                'borrowing_id' => $borrowing->id,
                'return_date' => now()->toDateString(),
                'quantity_returned' => $request->quantity_returned,
                'condition' => $request->condition,
                'notes' => $request->notes,
            ]);

            $tool->increment('available', $request->quantity_returned);

            if ((int) $request->quantity_returned === (int) $borrowing->quantity) {
                $borrowing->update(['status' => 'returned']);
            }

            $fineData = FineCalculatorService::calculate($borrowing, $return);
            $fine = Fine::updateOrCreate(
                ['borrowing_id' => $borrowing->id],
                [
                    ...$fineData,
                    'status' => 'unpaid',
                    'paid_at' => null,
                ]
            );

            return [
                'return' => $return,
                'fine' => $fine,
            ];
        });

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'return',
            'model' => 'Borrowing',
            'model_id' => $borrowing->id,
            'description' => 'Mencatat pengembalian untuk peminjaman #' . $borrowing->id,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Fine',
            'model_id' => $result['fine']->id,
            'description' => 'Membuat denda otomatis untuk peminjaman #' . $borrowing->id,
            'new_data' => $result['fine']->toArray(),
        ]);

        return redirect()->route('peminjam.borrowings.show', $borrowing)->with('success', 'Pengembalian berhasil dicatat. Denda otomatis dihitung jika ada keterlambatan atau kerusakan.');
    }
}
