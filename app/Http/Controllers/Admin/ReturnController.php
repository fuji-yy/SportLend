<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Return_model;
use App\Models\Borrowing;
use App\Models\ActivityLog;
use App\Models\Fine;
use App\Services\FineCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = Return_model::with(['borrowing.user', 'borrowing.tool']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('borrowing.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('borrowing.tool', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $returns = $query->paginate(15);
        return view('admin.returns.index', compact('returns'));
    }

    public function show(Return_model $return)
    {
        $return_model = $return;
        return view('admin.returns.show', compact('return_model'));
    }

    public function create()
    {
        $borrowings = Borrowing::with(['user', 'tool'])
            ->where('status', 'approved')
            ->whereDoesntHave('return')
            ->get();

        return view('admin.returns.create', compact('borrowings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'quantity_returned' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'notes' => 'nullable|string',
        ]);

        $result = DB::transaction(function () use ($request) {
            $borrowing = Borrowing::with('return')->lockForUpdate()->findOrFail($request->borrowing_id);
            $tool = $borrowing->tool()->lockForUpdate()->first();

            if ($borrowing->status !== 'approved') {
                abort(422, 'Hanya peminjaman berstatus approved yang bisa dikembalikan.');
            }

            if ($borrowing->return) {
                abort(422, 'Peminjaman ini sudah memiliki data pengembalian.');
            }

            if ($request->quantity_returned > $borrowing->quantity) {
                abort(422, 'Jumlah pengembalian tidak boleh melebihi jumlah peminjaman.');
            }

            $return = Return_model::create([
                'borrowing_id' => $request->borrowing_id,
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

        $return = $result['return'];
        $fine = $result['fine'];

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Return',
            'model_id' => $return->id,
            'description' => 'Membuat pengembalian untuk peminjaman #' . $return->borrowing_id,
            'new_data' => $return->toArray(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Fine',
            'model_id' => $fine->id,
            'description' => 'Membuat denda untuk peminjaman #' . $return->borrowing_id,
            'new_data' => $fine->toArray(),
        ]);

        return redirect()->route('admin.returns.index')->with('success', 'Pengembalian berhasil dicatat.');
    }

    public function edit(Return_model $return)
    {
        $return_model = $return;
        return view('admin.returns.edit', compact('return_model'));
    }

    public function update(Request $request, Return_model $return)
    {
        $request->validate([
            'quantity_returned' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'notes' => 'nullable|string',
        ]);

        $oldData = $return->toArray();

        $updatedFine = DB::transaction(function () use ($request, $return) {
            $borrowing = $return->borrowing()->lockForUpdate()->first();
            $tool = $borrowing->tool()->lockForUpdate()->first();

            if ($request->quantity_returned > $borrowing->quantity) {
                abort(422, 'Jumlah pengembalian tidak boleh melebihi jumlah peminjaman.');
            }

            $diff = (int) $request->quantity_returned - (int) $return->quantity_returned;

            if ($diff > 0) {
                $tool->increment('available', $diff);
            }

            if ($diff < 0) {
                $needed = abs($diff);
                if ($tool->available < $needed) {
                    abort(422, 'Stok tersedia tidak cukup untuk mengurangi jumlah pengembalian.');
                }
                $tool->decrement('available', $needed);
            }

            $return->update([
                'quantity_returned' => $request->quantity_returned,
                'condition' => $request->condition,
                'notes' => $request->notes,
            ]);

            $borrowing->update([
                'status' => ((int) $request->quantity_returned === (int) $borrowing->quantity) ? 'returned' : 'approved',
            ]);

            $fineData = FineCalculatorService::calculate($borrowing, $return);

            return Fine::updateOrCreate(
                ['borrowing_id' => $borrowing->id],
                [
                    ...$fineData,
                    'status' => 'unpaid',
                    'paid_at' => null,
                ]
            );
        });

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'Return',
            'model_id' => $return->id,
            'description' => 'Memperbarui data pengembalian #' . $return->id,
            'old_data' => $oldData,
            'new_data' => $return->fresh()->toArray(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'Fine',
            'model_id' => $updatedFine->id,
            'description' => 'Menghitung ulang denda untuk peminjaman #' . $return->borrowing_id,
            'new_data' => $updatedFine->toArray(),
        ]);

        return redirect()->route('admin.returns.show', $return)->with('success', 'Pengembalian berhasil diperbarui.');
    }

    public function destroy(Return_model $return)
    {
        $returnId = $return->id;

        $fineAction = DB::transaction(function () use ($return) {
            $borrowing = $return->borrowing()->lockForUpdate()->first();
            $tool = $borrowing->tool()->lockForUpdate()->first();

            if ($tool->available < $return->quantity_returned) {
                abort(422, 'Stok tersedia tidak cukup untuk menghapus data pengembalian ini.');
            }

            $tool->decrement('available', $return->quantity_returned);
            $borrowing->update(['status' => 'approved']);

            $fine = Fine::where('borrowing_id', $borrowing->id)->lockForUpdate()->first();
            $fineAction = null;
            if ($fine) {
                if ($fine->status === 'paid') {
                    $notes = trim(($fine->notes_admin ? $fine->notes_admin . "\n" : '') . 'Auto-waived karena data pengembalian dihapus pada ' . now()->format('d-m-Y H:i'));
                    $fine->update([
                        'status' => 'waived',
                        'notes_admin' => $notes,
                    ]);
                    $fineAction = ['action' => 'waived', 'fine' => $fine];
                } else {
                    $fineAction = ['action' => 'deleted', 'fine_id' => $fine->id];
                    $fine->delete();
                }
            }

            $return->delete();

            return $fineAction;
        });

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'model' => 'Return',
            'model_id' => $returnId,
            'description' => 'Menghapus pengembalian #' . $returnId,
        ]);

        if ($fineAction && $fineAction['action'] === 'waived') {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'update',
                'model' => 'Fine',
                'model_id' => $fineAction['fine']->id,
                'description' => 'Mengubah denda menjadi waived karena data pengembalian dihapus',
                'new_data' => $fineAction['fine']->toArray(),
            ]);
        }

        if ($fineAction && $fineAction['action'] === 'deleted') {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'delete',
                'model' => 'Fine',
                'model_id' => $fineAction['fine_id'],
                'description' => 'Menghapus denda karena data pengembalian dihapus',
            ]);
        }

        return redirect()->route('admin.returns.index')->with('success', 'Pengembalian berhasil dihapus.');
    }
}
