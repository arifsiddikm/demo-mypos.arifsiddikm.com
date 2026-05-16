<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\InventoryMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $movements = InventoryMovement::with(['ingredient', 'supplier', 'user'])
            ->orderByDesc('movement_date')
            ->paginate(20);
        return view('admin.ingredient.inventory', compact('movements'));
    }

    public function create()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        $suppliers   = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('admin.ingredient.inventory_create', compact('ingredients', 'suppliers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ingredient_id'  => 'required|exists:ingredients,id',
            'supplier_id'    => 'nullable|exists:suppliers,id',
            'type'           => 'required|in:in,out,adjustment',
            'quantity'       => 'required|numeric|min:0.001',
            'cost_per_unit'  => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string|max:255',
            'movement_date'  => 'required|date',
        ]);

        $data['user_id'] = auth()->id();
        InventoryMovement::create($data);

        // Update stock
        $ingredient = Ingredient::find($data['ingredient_id']);
        if ($data['type'] === 'in') {
            $ingredient->increment('stock', $data['quantity']);
        } elseif ($data['type'] === 'out') {
            $ingredient->decrement('stock', $data['quantity']);
        } else {
            $ingredient->update(['stock' => $data['quantity']]);
        }

        return redirect()->route('admin.inventory.index')->with('success', 'Pergerakan stok berhasil dicatat.');
    }
}
