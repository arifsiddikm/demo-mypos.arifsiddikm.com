@extends('layouts.admin')
@section('title','Supplier')
@section('page-title','Data Supplier')
@section('page-subtitle','Kelola supplier bahan cafe')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <span style="font-size:12px;color:#a06c3e;">{{ $suppliers->count() }} supplier</span>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Supplier
    </a>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:12px;">
    @forelse($suppliers as $s)
    <div class="card" style="transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(92,61,30,.1)'" onmouseout="this.style.boxShadow=''">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <div style="width:38px;height:38px;background:#f5deb3;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#5c3d1e;">
                {{ strtoupper(substr($s->name,0,2)) }}
            </div>
            @if($s->is_active)<span class="badge badge-green">Aktif</span>
            @else<span class="badge badge-gray">Nonaktif</span>@endif
        </div>
        <div style="font-size:14px;font-weight:700;color:#2e1d0e;margin-bottom:8px;">{{ $s->name }}</div>
        <div style="display:flex;flex-direction:column;gap:3px;margin-bottom:12px;">
            @if($s->contact_person)<div style="font-size:12px;color:#a06c3e;">👤 {{ $s->contact_person }}</div>@endif
            @if($s->phone)<div style="font-size:12px;color:#a06c3e;">📞 {{ $s->phone }}</div>@endif
            @if($s->email)<div style="font-size:12px;color:#a06c3e;">✉️ {{ $s->email }}</div>@endif
        </div>
        <div style="font-size:11px;color:#d4b08a;margin-bottom:12px;">{{ $s->ingredients_count }} bahan terdaftar</div>
        <div style="display:flex;gap:6px;">
            <a href="{{ route('admin.suppliers.edit',$s) }}" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center;">Edit</a>
            <form method="POST" action="{{ route('admin.suppliers.destroy',$s) }}" style="margin:0">
                @csrf @method('DELETE')
                <button type="button" class="btn btn-danger btn-sm" data-confirm="Hapus supplier {{ $s->name }}?">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;" class="card">
        <div class="empty-state"><span class="empty-icon">🏭</span><p>Belum ada supplier</p><small><a href="{{ route('admin.suppliers.create') }}" style="color:#5c3d1e;font-weight:600;">Tambahkan sekarang</a></small></div>
    </div>
    @endforelse
</div>
@endsection
