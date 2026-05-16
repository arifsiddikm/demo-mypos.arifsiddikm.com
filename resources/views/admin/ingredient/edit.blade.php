@extends('layouts.admin')
@section('title','Edit Bahan')
@section('page-title','Edit Bahan')
@section('content')
<div style="max-width:560px;"><div class="card">
<form method="POST" action="{{ route('admin.ingredients.update',$ingredient) }}">
    @csrf @method('PUT')
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div><label class="form-label">Nama Bahan <span style="color:#ef4444">*</span></label>
        <input type="text" name="name" value="{{ old('name',$ingredient->name) }}" class="form-input" required></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Satuan</label>
            <select name="unit" class="form-input">
                @foreach(['kg','gram','liter','ml','pcs','sachet','botol','karton'] as $u)
                <option value="{{ $u }}" {{ old('unit',$ingredient->unit)===$u?'selected':'' }}>{{ $u }}</option>@endforeach
            </select></div>
            <div><label class="form-label">Stok Saat Ini</label>
            <input type="text" value="{{ $ingredient->stock }} {{ $ingredient->unit }}" class="form-input" readonly>
            <p class="form-hint">Ubah stok via menu Inventory</p></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Min Stok</label><input type="number" name="min_stock" value="{{ old('min_stock',$ingredient->min_stock) }}" class="form-input" min="0" step="0.001"></div>
            <div><label class="form-label">Harga/Unit (Rp)</label>
            <div class="input-group"><span class="input-addon">Rp</span><input type="number" name="cost_per_unit" value="{{ old('cost_per_unit',$ingredient->cost_per_unit) }}" class="form-input" min="0"></div></div>
        </div>
        <div><label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-input">
            <option value="">— Tidak ada —</option>
            @foreach($suppliers as $s)<option value="{{ $s->id }}" {{ old('supplier_id',$ingredient->supplier_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
        </select></div>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.ingredients.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
</div></div>
@endsection
