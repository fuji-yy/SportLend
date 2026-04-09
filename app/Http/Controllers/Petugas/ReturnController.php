<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Return_model;
use App\Services\FineCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'tool'])->where('status', 'approved');

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
        return view('petugas.returns.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        $return = $borrowing->return;
        return view('petugas.returns.show', compact('borrowing', 'return'));
    }

    public function store(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'quantity_returned' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'notes' => 'nullable|string|max:500',
        ]);

        $result = DB::transaction(function () use ($request, $borrowing) {
            $borrowing = Borrowing::with('return')->lockForUpdate()->findOrFail($borrowing->id);
            $tool = $borrowing->tool()->lockForUpdate()->first();

            if ($borrowing->status !== 'approved') {
                abort(422, 'Peminjaman tidak dalam status approved.');
            }

            if ($borrowing->return) {
                abort(422, 'Peminjaman ini sudah memiliki pengembalian.');
            }

            if ($request->quantity_returned > $borrowing->quantity) {
                abort(422, 'Jumlah pengembalian tidak boleh melebihi jumlah peminjaman.');
            }

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

        $createdReturn = $result['return'];
        $fine = $result['fine'];

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Return',
            'model_id' => $createdReturn->id,
            'description' => 'Mencatat pengembalian untuk peminjaman #' . $borrowing->id,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Fine',
            'model_id' => $fine->id,
            'description' => 'Membuat denda untuk peminjaman #' . $borrowing->id,
            'new_data' => $fine->toArray(),
        ]);

        return redirect()->route('petugas.returns.show', $borrowing)->with('success', 'Pengembalian berhasil dicatat.');
    }

    public function update(Request $request, Return_model $return_model)
    {
        $request->validate([
            'quantity_returned' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
            'notes' => 'nullable|string|max:500',
        ]);

        $updatedFine = DB::transaction(function () use ($request, $return_model) {
            $return = Return_model::lockForUpdate()->findOrFail($return_model->id);
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
            'model_id' => $return_model->id,
            'description' => 'Memperbarui pengembalian untuk peminjaman #' . $return_model->borrowing_id,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'Fine',
            'model_id' => $updatedFine->id,
            'description' => 'Menghitung ulang denda untuk peminjaman #' . $return_model->borrowing_id,
            'new_data' => $updatedFine->toArray(),
        ]);

        return redirect()->route('petugas.returns.show', $return_model->borrowing_id)->with('success', 'Pengembalian berhasil diperbarui.');
    }

    public function destroy(Return_model $return_model)
    {
        $borrowingId = $return_model->borrowing_id;

        $fineAction = DB::transaction(function () use ($return_model) {
            $return = Return_model::lockForUpdate()->findOrFail($return_model->id);
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
            'model_id' => $return_model->id,
            'description' => 'Menghapus pengembalian untuk peminjaman #' . $borrowingId,
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

        return redirect()->route('petugas.returns.show', $borrowingId)->with('success', 'Data pengembalian berhasil dihapus.');
    }

    public function monitor()
    {
        $returns = Return_model::with(['borrowing.user', 'borrowing.tool'])
            ->latest()
            ->paginate(15);

        return view('petugas.returns.monitor', compact('returns'));
    }
}
