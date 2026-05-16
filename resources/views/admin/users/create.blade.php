@extends('layouts.admin')
@section('title','Tambah User')
@section('page-title','Tambah User')
@section('content')
<div style="max-width:500px;"><div class="card">
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div><label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
        @error('name')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label">Email <span style="color:#ef4444">*</span></label>
        <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
        @error('email')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label">Role</label>
        <select name="role" class="form-input">
            <option value="kasir" {{ old('role')==='kasir'?'selected':'' }}>Kasir</option>
            <option value="admin" {{ old('role')==='admin'?'selected':'' }}>Admin</option>
        </select></div>
        <div><label class="form-label">Password <span style="color:#ef4444">*</span></label>
        <input type="password" name="password" class="form-input" required minlength="6">
        @error('password')<p class="form-error">{{ $message }}</p>@enderror</div>
        <div><label class="form-label">Konfirmasi Password <span style="color:#ef4444">*</span></label>
        <input type="password" name="password_confirmation" class="form-input" required></div>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
</div></div>
@endsection
