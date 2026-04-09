<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        $query = Tool::with('category');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $tools = $query->paginate(15);
        return view('admin.tools.index', compact('tools'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|unique:tools',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $tool = Tool::create([
            ...$request->all(),
            'available' => $request->quantity,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Tool',
            'model_id' => $tool->id,
            'description' => 'Membuat alat ' . $tool->name,
            'new_data' => $tool->toArray(),
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(Tool $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|unique:tools,code,' . $tool->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $oldData = $tool->toArray();
        $tool->update($request->all());

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'Tool',
            'model_id' => $tool->id,
            'description' => 'Mengupdate alat ' . $tool->name,
            'old_data' => $oldData,
            'new_data' => $tool->toArray(),
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil diupdate.');
    }

    public function destroy(Tool $tool)
    {
        $toolName = $tool->name;
        $tool->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'model' => 'Tool',
            'model_id' => $tool->id,
            'description' => 'Menghapus alat ' . $toolName,
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil dihapus.');
    }
}
