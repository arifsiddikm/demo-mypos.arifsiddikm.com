@extends('layouts.admin')
@section('title','Edit User')
@section('page-title','Edit User')
@section('content')
<div style="max-width:500px;"><div class="card">
<form method="POST" action="{{ route('admin.users.update',$user) }}">
    @csrf @method('PUT')
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div><label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
        <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-input" required></div>
        <div><label class="form-label">Email <span style="color:#ef4444">*</span></label>
        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-input" required></div>
        <div><label class="form-label">Role</label>
        <select name="role" class="form-input">
            <option value="kasir" {{ old('role',$user->role)==='kasir'?'selected':'' }}>Kasir</option>
            <option value="admin" {{ old('role',$user->role)==='admin'?'selected':'' }}>Admin</option>
        </select></div>
        <div><label class="form-label">Password Baru <span style="font-size:11px;color:#a06c3e;font-weight:400;">(kosongkan jika tidak diubah)</span></label>
        <input type="password" name="password" class="form-input" minlength="6"></div>
        <div><label class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-input"></div>
        <label class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ $user->is_active?'checked':'' }}>
            <span class="form-check-label">Akun Aktif</span>
        </label>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
</div></div>
@endsection
