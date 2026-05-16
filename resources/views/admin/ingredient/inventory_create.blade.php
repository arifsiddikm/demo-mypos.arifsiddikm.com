@extends('layouts.admin')
@section('title','Input Stok')
@section('page-title','Input Pergerakan Stok')
@section('page-subtitle','Catat stok masuk, keluar, atau penyesuaian')
@section('content')
<div style="max-width:560px;"><div class="card">
<form method="POST" action="{{ route('admin.inventory.store') }}">
    @csrf
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div><label class="form-label">Bahan <span style="color:#ef4444">*</span></label>
        <select name="ingredient_id" class="form-input" required id="ing-select">
            <option value="">Pilih Bahan</option>
            @foreach($ingredients as $ing)
            <option value="{{ $ing->id }}" data-stock="{{ $ing->stock }}" data-unit="{{ $ing->unit }}" {{ old('ingredient_id')==$ing->id?'selected':'' }}>
                {{ $ing->name }} (Stok: {{ $ing->stock }} {{ $ing->unit }})
            </option>@endforeach
        </select>
        <p class="form-hint" id="stock-info"></p>
        @error('ingredient_id')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Tipe <span style="color:#ef4444">*</span></label>
            <select name="type" class="form-input" required>
                <option value="in" {{ old('type')==='in'?'selected':'' }}>↑ Masuk</option>
                <option value="out" {{ old('type')==='out'?'selected':'' }}>↓ Keluar</option>
                <option value="adjustment" {{ old('type')==='adjustment'?'selected':'' }}>⇄ Penyesuaian</option>
            </select></div>
            <div><label class="form-label">Jumlah <span style="color:#ef4444">*</span></label>
            <input type="number" name="quantity" value="{{ old('quantity') }}" class="form-input" min="0.001" step="0.001" required>
            @error('quantity')<p class="form-error">{{ $message }}</p>@enderror</div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Harga/Unit (Rp)</label>
            <div class="input-group"><span class="input-addon">Rp</span><input type="number" name="cost_per_unit" value="{{ old('cost_per_unit') }}" class="form-input" min="0"></div></div>
            <div><label class="form-label">Supplier</label>
            <select name="supplier_id" class="form-input"><option value="">— Tidak ada —</option>
            @foreach($suppliers as $s)<option value="{{ $s->id }}" {{ old('supplier_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
            </select></div>
        </div>
        <div><label class="form-label">Tanggal <span style="color:#ef4444">*</span></label>
        <input type="datetime-local" name="movement_date" value="{{ old('movement_date',now()->format('Y-m-d\TH:i')) }}" class="form-input" required></div>
        <div><label class="form-label">Catatan</label><input type="text" name="notes" value="{{ old('notes') }}" class="form-input" placeholder="Opsional"></div>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
</div></div>
@endsection
@section('scripts')
<script>
document.getElementById('ing-select').addEventListener('change',function(){
    const opt=this.options[this.selectedIndex];
    const info=document.getElementById('stock-info');
    info.textContent=opt.dataset.stock!==undefined?'Stok saat ini: '+opt.dataset.stock+' '+opt.dataset.unit:'';
});
</script>
@endsection
