<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Table;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $tables     = Table::where('is_active', true)->orderBy('name')->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('pos.index', compact('tables', 'categories'));
    }

    public function menus(Request $request)
    {
        $query = Menu::with('category')->where('is_available', true);

        if ($request->category && $request->category !== 'all') {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->orderBy('sort_order')->get());
    }
}
