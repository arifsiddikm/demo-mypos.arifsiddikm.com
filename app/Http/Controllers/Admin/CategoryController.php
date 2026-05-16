<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menus')->orderBy('sort_order')->get();
        return view('admin.menu.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:50',
            'icon'       => 'nullable|string|max:10',
            'sort_order' => 'integer',
        ]);
        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = true;
        Category::create($data);
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => 'required|string|max:50', 'icon' => 'nullable|string|max:10', 'sort_order' => 'integer', 'is_active' => 'boolean']);
        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);
        return back()->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->slug === 'all') return back()->with('error', 'Kategori default tidak bisa dihapus.');
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
