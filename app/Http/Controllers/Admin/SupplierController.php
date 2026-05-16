<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('ingredients')->orderBy('name')->get();
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function create() { return view('admin.supplier.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
        ]);
        $data['is_active'] = true;
        Supplier::create($data);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier) { return view('admin.supplier.edit', compact('supplier')); }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:100',
            'address'        => 'nullable|string',
            'is_active'      => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $supplier->update($data);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier dihapus.');
    }
}
