@extends('layouts.admin')
@section('title','Data Bahan')
@section('page-title','Data Bahan')
@section('page-subtitle','Kelola stok bahan baku cafe')
@section('content')

@php $isAdmin = auth()->user()->role === 'admin'; @endphp

<div style="display:flex;gap:8px;margin-bottom:16px;align-items:center;">
    @if($isAdmin)
    <a href="{{ route('admin.ingredients.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Tambah Bahan
    </a>
    <a href="{{ route('admin.inventory.create') }}" class="btn btn-ghost">📦 Input Stok</a>
    @else
    <span class="badge badge-yellow" style="font-size:12px;padding:7px 12px;">👁️ Mode View Only — Kasir tidak bisa menambah/edit bahan</span>
    @endif
</div>

<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr>
                <th>Bahan</th><th>Supplier</th><th>Stok</th><th>Min Stok</th><th>Harga/Unit</th><th>Status</th>
                @if($isAdmin)<th style="text-align:right">Aksi</th>@endif
            </tr></thead>
            <tbody>
                @forelse($ingredients as $ing)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#2e1d0e;">{{ $ing->name }}</div>
                        <div style="font-size:11px;color:#a06c3e;">Satuan: {{ $ing->unit }}</div>
                    </td>
                    <td class="muted">{{ $ing->supplier?->name ?? '—' }}</td>
                    <td>
                        <span style="font-weight:700;color:{{ $ing->isLowStock()?'#dc2626':'#2e1d0e' }};">
                            {{ number_format($ing->stock,2) }}
                        </span>
                        <span style="font-size:11px;color:#a06c3e;"> {{ $ing->unit }}</span>
                    </td>
                    <td class="muted">{{ number_format($ing->min_stock,2) }} {{ $ing->unit }}</td>
                    <td style="color:#5c3d1e;">Rp {{ number_format($ing->cost_per_unit,0,',','.') }}</td>
                    <td>
                        @if($ing->isLowStock())
                        <span class="badge badge-red">⚠️ Stok Rendah</span>
                        @else
                        <span class="badge badge-green">✅ Cukup</span>
                        @endif
                    </td>
                    @if($isAdmin)
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px;">
                            <a href="{{ route('admin.ingredients.edit',$ing) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.ingredients.destroy',$ing) }}" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" data-confirm="Hapus bahan {{ $ing->name }}?">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="{{ $isAdmin ? 7 : 6 }}" style="text-align:center;padding:40px;color:#d4b08a;">
                    Belum ada data bahan.
                    @if($isAdmin)<a href="{{ route('admin.ingredients.create') }}" style="color:#5c3d1e;font-weight:600;">Tambahkan</a>@endif
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
