@extends('layouts.admin')
@section('title','Edit Supplier')
@section('page-title','Edit Supplier')
@section('content')
<div style="max-width:560px;"><div class="card">
<form method="POST" action="{{ route('admin.suppliers.update',$supplier) }}">
    @csrf @method('PUT')
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div><label class="form-label">Nama Perusahaan <span style="color:#ef4444">*</span></label>
        <input type="text" name="name" value="{{ old('name',$supplier->name) }}" class="form-input" required></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Contact Person</label><input type="text" name="contact_person" value="{{ old('contact_person',$supplier->contact_person) }}" class="form-input"></div>
            <div><label class="form-label">No. Telepon</label><input type="text" name="phone" value="{{ old('phone',$supplier->phone) }}" class="form-input"></div>
        </div>
        <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email',$supplier->email) }}" class="form-input"></div>
        <div><label class="form-label">Alamat</label><textarea name="address" rows="3" class="form-input">{{ old('address',$supplier->address) }}</textarea></div>
        <label class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $supplier->is_active?'checked':'' }}>
            <span class="form-check-label">Supplier Aktif</span>
        </label>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
</div></div>
@endsection
