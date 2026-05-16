@extends('layouts.admin')
@section('title','Tambah Menu')
@section('page-title','Tambah Menu')
@section('page-subtitle','Tambahkan item menu baru ke cafe')
@section('content')
<div style="max-width:640px;">
<div class="card">
<form method="POST" action="{{ route('admin.menus.store') }}" enctype="multipart/form-data">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div style="grid-column:1/-1;">
            <label class="form-label">Nama Menu <span style="color:#ef4444">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Kopi Susu Gula Aren" required>
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Kategori <span style="color:#ef4444">*</span></label>
            <select name="category_id" class="form-input" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Harga (Rp) <span style="color:#ef4444">*</span></label>
            <div class="input-group">
                <span class="input-addon">Rp</span>
                <input type="number" name="price" value="{{ old('price') }}" class="form-input" placeholder="25000" min="0" required>
            </div>
            @error('price')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div style="grid-column:1/-1;">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="3" class="form-input" placeholder="Deskripsi singkat menu...">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="form-label">Foto Menu</label>
            <input type="file" name="image" accept="image/*" class="form-input" id="img-input">
            <img id="img-preview" class="hidden" style="margin-top:8px;width:80px;height:80px;object-fit:cover;border-radius:10px;border:1.5px solid #f5deb3;">
            <p class="form-hint">JPG/PNG, max 2MB</p>
        </div>
        <div>
            <label class="form-label">Urutan Tampil</label>
            <input type="number" name="sort_order" value="{{ old('sort_order',0) }}" class="form-input" min="0">
        </div>
        <div style="grid-column:1/-1;">
            <label class="toggle-wrap" style="cursor:pointer;">
                <div class="toggle-switch">
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available',1)?'checked':'' }}>
                    <span class="toggle-track"></span>
                </div>
                <span class="toggle-label">Menu Tersedia</span>
            </label>
        </div>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan Menu</button>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
</div>
</div>
@endsection
@section('scripts')
<script>
document.getElementById('img-input').addEventListener('change',function(){
    const p=document.getElementById('img-preview');
    if(this.files[0]){const r=new FileReader();r.onload=e=>{p.src=e.target.result;p.style.display='block';};r.readAsDataURL(this.files[0]);}
});
</script>
@endsection
