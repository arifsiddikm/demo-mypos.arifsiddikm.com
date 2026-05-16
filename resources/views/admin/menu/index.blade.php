@extends('layouts.admin')
@section('title','Data Menu')
@section('page-title','Data Menu')
@section('page-subtitle','Kelola menu dan harga cafe')
@section('content')

@php $isAdmin = auth()->user()->role === 'admin'; @endphp

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
    <div style="display:flex;gap:8px;">
        @if($isAdmin)
        <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Menu
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">🏷️ Kategori</a>
        @else
        <span class="badge badge-yellow" style="font-size:12px;padding:7px 12px;">👁️ Mode View Only — Kasir tidak bisa menambah/edit menu</span>
        @endif
    </div>
</div>

<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:12px 16px;border-bottom:1px solid #f5deb3;display:flex;align-items:center;gap:8px;">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#d4b08a" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="search" placeholder="Cari menu..." style="border:none;outline:none;font-size:13px;color:#2e1d0e;background:transparent;flex:1;font-family:inherit;">
    </div>
    <div class="table-wrap">
        <table class="data-table" id="menu-table">
            <thead><tr>
                <th>#</th><th>Nama Menu</th><th>Kategori</th><th>Harga</th><th>Status</th>
                @if($isAdmin)<th style="text-align:right">Aksi</th>@endif
            </tr></thead>
            <tbody>
                @forelse($menus as $i=>$menu)
                <tr>
                    <td class="muted">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:42px;height:42px;background:#faf0dc;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;overflow:hidden;flex-shrink:0;">
                                @if($menu->image)
                                    @if(str_starts_with($menu->image, 'http'))
                                        <img src="{{ $menu->image }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;" alt="" onerror="this.style.display='none';this.parentElement.innerHTML='{{ $menu->category->icon ?? '☕' }}'">
                                    @else
                                        <img src="{{ asset('storage/'.$menu->image) }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;" alt="">
                                    @endif
                                @else {{ $menu->category->icon ?? '☕' }}@endif
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:#2e1d0e;">{{ $menu->name }}</div>
                                @if($menu->description)<div style="font-size:11px;color:#a06c3e;">{{ Str::limit($menu->description,50) }}</div>@endif
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-coffee">{{ $menu->category->name }}</span></td>
                    <td style="font-weight:600;color:#5c3d1e;">Rp {{ number_format($menu->price,0,',','.') }}</td>
                    <td>
                        @if($menu->is_available)<span class="badge badge-green">Tersedia</span>
                        @else<span class="badge badge-red">Nonaktif</span>@endif
                    </td>
                    @if($isAdmin)
                    <td>
                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">
                            <a href="{{ route('admin.menus.edit',$menu) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.menus.destroy',$menu) }}" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" data-confirm="Hapus menu {{ $menu->name }}?">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ $isAdmin ? 6 : 5 }}" style="text-align:center;padding:40px;color:#d4b08a;">
                    Belum ada menu.
                    @if($isAdmin)<a href="{{ route('admin.menus.create') }}" style="color:#5c3d1e;font-weight:600;">Tambahkan</a>@endif
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
<script>
document.getElementById('search').addEventListener('input',function(){
    const q=this.value.toLowerCase();
    document.querySelectorAll('#menu-table tbody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(q)?'':'none';});
});
</script>
@endsection
