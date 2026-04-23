<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        $query = Tool::with('category');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $tools = $query->paginate(15);
        $categories = Category::orderBy('name')->get();

        return view('admin.tools.index', compact('tools', 'categories'));
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
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $coverImagePath = $request->hasFile('cover_image')
            ? $request->file('cover_image')->store('book-covers', 'public')
            : null;

        $tool = Tool::create([
            ...$request->except('cover_image'),
            'cover_image' => $coverImagePath,
            'available' => $request->quantity,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'model' => 'Tool',
            'model_id' => $tool->id,
            'description' => 'Membuat buku ' . $tool->name,
            'new_data' => $tool->toArray(),
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Tool $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function show(Tool $tool)
    {
        $tool->load('category');

        return view('admin.tools.show', compact('tool'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'code' => 'required|unique:tools,code,' . $tool->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        $oldData = $tool->toArray();
        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            if ($tool->cover_image && Storage::disk('public')->exists($tool->cover_image)) {
                Storage::disk('public')->delete($tool->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        $tool->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'model' => 'Tool',
            'model_id' => $tool->id,
            'description' => 'Mengupdate buku ' . $tool->name,
            'old_data' => $oldData,
            'new_data' => $tool->toArray(),
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy(Tool $tool)
    {
        $toolName = $tool->name;

        if ($tool->cover_image && Storage::disk('public')->exists($tool->cover_image)) {
            Storage::disk('public')->delete($tool->cover_image);
        }

        $tool->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'model' => 'Tool',
            'model_id' => $tool->id,
            'description' => 'Menghapus buku ' . $toolName,
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Buku berhasil dihapus.');
    }
}
