<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Category;

class ToolController extends Controller
{
    public function index()
    {
        $query = Tool::with('category')->where('available', '>', 0);

        if (request()->filled('name')) {
            $query->where('name', 'like', '%' . request('name') . '%');
        }

        if (request()->filled('category_id')) {
            $query->where('category_id', request('category_id'));
        }

        if (request()->filled('condition')) {
            $query->where('condition', request('condition'));
        }

        $tools = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('peminjam.tools.index', compact('tools', 'categories'));
    }

    public function show(Tool $tool)
    {
        return view('peminjam.tools.show', compact('tool'));
    }

    public function search()
    {
        return $this->index();
    }
}
