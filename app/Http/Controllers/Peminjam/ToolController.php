<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Category;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::with('category')->where('available', '>', 0)->paginate(12);
        $categories = Category::all();
        
        return view('peminjam.tools.index', compact('tools', 'categories'));
    }

    public function show(Tool $tool)
    {
        return view('peminjam.tools.show', compact('tool'));
    }

    public function search()
    {
        $query = request()->get('q');
        $tools = Tool::with('category')
            ->where('available', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(12);

        $categories = Category::all();
        return view('peminjam.tools.index', compact('tools', 'categories'));
    }
}
