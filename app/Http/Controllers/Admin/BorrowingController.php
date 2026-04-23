<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\ActivityLog;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function create()
    {
        $users = User::where('role', 'peminjam')->orderBy('name')->get();
        $tools = Tool::orderBy('name')->get();

        return view('admin.borrowings.create', compact('users', 'tools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tool_id' => 'required|exists:tools,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
            'purpose' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,returned',
        ]);

        $borrowing = DB::transaction(function () use ($request) {
            $tool = Tool::query()->lockForUpdate()->findOrFail($request->tool_id);

            if ($request->status === 'approved' && $tool->available < $request->quantity) {
                abort(422, 'Stok buku tidak mencukupi untuk status disetujui.');
            }

            $borrowing = Borrowing::create([
                'user_id' => $request->user_id,
                'tool_id' => $request->tool_id,
                'quantity' => $request->quantity,
                'borrow_date' => $request->borrow_date,
                'due_date' => $request->due_date,
                'purpose' => $request->purpose,
                'notes' => $request->notes,
                'status' => $request->status,
            ]);

            if ($request->status === 'approved') {
                $tool->decrement('available', $request->quantity);
            }

            return $borrowing;
        });

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Borrowing',
            'model_id' => $borrowing->id,
            'description' => 'Membuat peminjaman baru #' . $borrowing->id,
            'new_data' => $borrowing->toArray(),
        ]);

        return redirect()->route('admin.borrowings.show', $borrowing)->with('success', 'Peminjaman berhasil dibuat.');
    }

    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'tool']);

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
        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function edit(Borrowing $borrowing)
    {
        $users = User::where('role', 'peminjam')->orderBy('name')->get();
        $tools = Tool::orderBy('name')->get();

        return view('admin.borrowings.edit', compact('borrowing', 'users', 'tools'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tool_id' => 'required|exists:tools,id',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
            'purpose' => 'nullable|string|max:500',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,returned',
        ]);

        $oldData = $borrowing->toArray();

        DB::transaction(function () use ($request, $borrowing) {
            $oldTool = Tool::query()->lockForUpdate()->findOrFail($borrowing->tool_id);
            $newTool = $oldTool->id === (int) $request->tool_id
                ? $oldTool
                : Tool::query()->lockForUpdate()->findOrFail($request->tool_id);

            // Revert old approved allocation first.
            if ($borrowing->status === 'approved') {
                $oldTool->increment('available', $borrowing->quantity);
            }

            // Apply new approved allocation if needed.
            if ($request->status === 'approved') {
                if ($newTool->available < $request->quantity) {
                    abort(422, 'Stok buku tidak mencukupi untuk status disetujui.');
                }

                $newTool->decrement('available', $request->quantity);
            }

            $borrowing->update([
                'user_id' => $request->user_id,
                'tool_id' => $request->tool_id,
                'quantity' => $request->quantity,
                'borrow_date' => $request->borrow_date,
                'due_date' => $request->due_date,
                'purpose' => $request->purpose,
                'notes' => $request->notes,
                'status' => $request->status,
            ]);
        });

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'Borrowing',
            'model_id' => $borrowing->id,
            'description' => 'Memperbarui data peminjaman #' . $borrowing->id,
            'old_data' => $oldData,
            'new_data' => $borrowing->fresh()->toArray(),
        ]);

        return redirect()->route('admin.borrowings.show', $borrowing)->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,returned',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $borrowing->status;
        DB::transaction(function () use ($request, $borrowing, $oldStatus) {
            $tool = $borrowing->tool()->lockForUpdate()->first();

            if ($request->status === 'approved' && $oldStatus !== 'approved') {

                if ($tool->available < $borrowing->quantity) {
                    abort(422, 'Stok buku tidak mencukupi untuk disetujui.');
                }

                $tool->decrement('available', $borrowing->quantity);
            }

            if ($oldStatus === 'approved' && $request->status !== 'approved') {
                $tool->increment('available', $borrowing->quantity);
            }

            $borrowing->update([
                'status' => $request->status,
                'notes' => $request->notes,
            ]);
        });

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update_status',
            'model' => 'Borrowing',
            'model_id' => $borrowing->id,
            'description' => "Mengubah status peminjaman dari {$oldStatus} menjadi {$request->status}",
            'new_data' => $borrowing->toArray(),
        ]);

        return redirect()->route('admin.borrowings.show', $borrowing)->with('success', 'Status berhasil diupdate.');
    }

    public function destroy(Borrowing $borrowing)
    {
        if (!in_array($borrowing->status, ['pending', 'rejected'], true)) {
            return back()->with('error', 'Hanya peminjaman dengan status menunggu atau ditolak yang bisa dihapus.');
        }

        $borrowing->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'model' => 'Borrowing',
            'model_id' => $borrowing->id,
            'description' => 'Menghapus peminjaman dari ' . $borrowing->user->name,
        ]);

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil dihapus.');
    }
}
