@extends('layouts.admin')
@section('title','Tambah Supplier')
@section('page-title','Tambah Supplier')
@section('content')
<div style="max-width:560px;"><div class="card">
<form method="POST" action="{{ route('admin.suppliers.store') }}">
    @csrf
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div><label class="form-label">Nama Perusahaan <span style="color:#ef4444">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="PT. Kopi Nusantara">
        @error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Contact Person</label><input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-input" placeholder="Budi Santoso"></div>
            <div><label class="form-label">No. Telepon</label><input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="08xxx"></div>
        </div>
        <div><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-input"></div>
        <div><label class="form-label">Alamat</label><textarea name="address" rows="3" class="form-input">{{ old('address') }}</textarea></div>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
</div></div>
@endsection
