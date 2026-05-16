@extends('layouts.admin')
@section('title','Tambah Bahan')
@section('page-title','Tambah Bahan')
@section('content')
<div style="max-width:560px;"><div class="card">
<form method="POST" action="{{ route('admin.ingredients.store') }}">
    @csrf
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div><label class="form-label">Nama Bahan <span style="color:#ef4444">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="Biji Kopi Arabika">
        @error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Satuan <span style="color:#ef4444">*</span></label>
            <select name="unit" class="form-input" required>
                @foreach(['kg','gram','liter','ml','pcs','sachet','botol','karton'] as $u)
                <option value="{{ $u }}" {{ old('unit')===$u?'selected':'' }}>{{ $u }}</option>@endforeach
            </select></div>
            <div><label class="form-label">Stok Awal</label><input type="number" name="stock" value="{{ old('stock',0) }}" class="form-input" min="0" step="0.001"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Minimum Stok</label><input type="number" name="min_stock" value="{{ old('min_stock',0) }}" class="form-input" min="0" step="0.001"><p class="form-hint">Alert jika stok ≤ nilai ini</p></div>
            <div><label class="form-label">Harga/Unit (Rp)</label>
            <div class="input-group"><span class="input-addon">Rp</span><input type="number" name="cost_per_unit" value="{{ old('cost_per_unit',0) }}" class="form-input" min="0"></div></div>
        </div>
        <div><label class="form-label">Supplier</label>
        <select name="supplier_id" class="form-input">
            <option value="">— Tidak ada —</option>
            @foreach($suppliers as $s)<option value="{{ $s->id }}" {{ old('supplier_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
        </select></div>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.ingredients.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
</div></div>
@endsection
