<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::with('supplier')->orderBy('name')->get();
        return view('admin.ingredient.index', compact('ingredients'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('admin.ingredient.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'name'          => 'required|string|max:100',
            'unit'          => 'required|string|max:20',
            'stock'         => 'required|numeric|min:0',
            'min_stock'     => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
        ]);
        Ingredient::create($data);
        return redirect()->route('admin.ingredients.index')->with('success', 'Bahan berhasil ditambahkan.');
    }

    public function edit(Ingredient $ingredient)
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('admin.ingredient.edit', compact('ingredient', 'suppliers'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $data = $request->validate([
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'name'          => 'required|string|max:100',
            'unit'          => 'required|string|max:20',
            'min_stock'     => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
        ]);
        $ingredient->update($data);
        return redirect()->route('admin.ingredients.index')->with('success', 'Bahan diperbarui.');
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->route('admin.ingredients.index')->with('success', 'Bahan dihapus.');
    }
}
