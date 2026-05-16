@extends('layouts.admin')
@section('title','Users')
@section('page-title','Manajemen Users')
@section('page-subtitle','Kelola akun admin dan kasir')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <span style="font-size:12px;color:#a06c3e;">{{ $users->count() }} akun</span>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah User
    </a>
</div>
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>User</th><th>Role</th><th>Status</th><th>Bergabung</th><th style="text-align:right">Aksi</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td><div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:34px;height:34px;border-radius:50%;background:#f5deb3;color:#5c3d1e;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">{{ strtoupper(substr($user->name,0,1)) }}</div>
                        <div><div style="font-weight:600;font-size:13px;color:#2e1d0e;">{{ $user->name }}</div><div style="font-size:11px;color:#a06c3e;">{{ $user->email }}</div></div>
                    </div></td>
                    <td>@if($user->role==='admin')<span class="badge badge-blue">👑 Admin</span>@else<span class="badge badge-gray">🧑‍💼 Kasir</span>@endif</td>
                    <td>@if($user->is_active)<span class="badge badge-green">Aktif</span>@else<span class="badge badge-red">Nonaktif</span>@endif</td>
                    <td class="muted">{{ $user->created_at->format('d M Y') }}</td>
                    <td><div style="display:flex;justify-content:flex-end;gap:6px;">
                        <a href="{{ route('admin.users.edit',$user) }}" class="btn btn-secondary btn-sm">Edit</a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy',$user) }}" style="margin:0">@csrf @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm" data-confirm="Hapus user {{ $user->name }}?">Hapus</button></form>
                        @endif
                    </div></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
