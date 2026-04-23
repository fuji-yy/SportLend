<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Return_model;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'peminjaman');
        $borrowings = null;
        $returns = null;

        if ($tab === 'pengembalian') {
            $query = Return_model::with(['borrowing.user', 'borrowing.tool.category', 'borrowing.fine'])->latest();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('borrowing.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('borrowing.tool', function ($toolQuery) use ($search) {
                        $toolQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('code', 'like', '%' . $search . '%');
                    });
                });
            }

            if ($request->filled('condition')) {
                $query->where('condition', $request->condition);
            }

            $returns = $query->paginate(15)->withQueryString();
        } else {
            $query = Borrowing::with(['user', 'tool.category', 'return', 'fine'])->latest();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('tool', function ($toolQuery) use ($search) {
                        $toolQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('code', 'like', '%' . $search . '%');
                    });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $borrowings = $query->paginate(15)->withQueryString();
        }

        return view('admin.status.index', compact('tab', 'borrowings', 'returns'));
    }
}
