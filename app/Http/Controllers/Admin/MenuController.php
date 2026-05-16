<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->orderBy('category_id')->orderBy('sort_order')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->where('slug', '!=', 'all')->orderBy('sort_order')->get();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:100',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'sort_order'   => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $data['is_available'] = $request->boolean('is_available', true);
        Menu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::where('is_active', true)->where('slug', '!=', 'all')->orderBy('sort_order')->get();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:100',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'image'        => 'nullable|image|max:2048',
            'is_available' => 'boolean',
            'sort_order'   => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image) Storage::disk('public')->delete($menu->image);
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $data['is_available'] = $request->boolean('is_available');
        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image) Storage::disk('public')->delete($menu->image);
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
