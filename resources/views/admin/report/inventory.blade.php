@extends('layouts.admin')
@section('title','Laporan Inventory')
@section('page-title','Laporan Inventory')
@section('page-subtitle','Pergerakan stok bahan baku')
@section('content')
<div class="card" style="margin-bottom:16px;">
    <form method="GET" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px;">
        <div><label class="form-label">Dari</label><input type="date" name="from" value="{{ $from }}" class="form-input" style="width:auto;"></div>
        <div><label class="form-label">Sampai</label><input type="date" name="to" value="{{ $to }}" class="form-input" style="width:auto;"></div>
        <div><label class="form-label">Tipe</label>
        <select name="type" class="form-input" style="width:auto;">
            <option value="all" {{ $type==='all'?'selected':'' }}>Semua</option>
            <option value="in" {{ $type==='in'?'selected':'' }}>Masuk</option>
            <option value="out" {{ $type==='out'?'selected':'' }}>Keluar</option>
            <option value="adjustment" {{ $type==='adjustment'?'selected':'' }}>Penyesuaian</option>
        </select></div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.reports.inventory.pdf',request()->all()) }}" target="_blank" class="btn btn-ghost">📄 PDF</a>
        <a href="{{ route('admin.reports.inventory.excel',request()->all()) }}" class="btn btn-ghost">📊 Excel</a>
    </form>
</div>
<div class="card" style="margin-bottom:16px;">
    <div style="font-size:13px;font-weight:700;color:#2e1d0e;margin-bottom:12px;">Ringkasan Stok Saat Ini</div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:8px;">
        @foreach($ingredients as $ing)
        <div style="background:{{ $ing->isLowStock()?'#fef2f2':'#fdf8f0' }};border:1px solid {{ $ing->isLowStock()?'#fecaca':'#f5deb3' }};border-radius:10px;padding:10px 12px;">
            <div style="font-size:11.5px;font-weight:600;color:#2e1d0e;margin-bottom:2px;">{{ $ing->name }}</div>
            <div style="font-size:16px;font-weight:700;color:{{ $ing->isLowStock()?'#dc2626':'#5c3d1e' }};">{{ number_format($ing->stock,2) }}</div>
            <div style="font-size:10.5px;color:#a06c3e;">{{ $ing->unit }} · min {{ $ing->min_stock }}</div>
        </div>
        @endforeach
    </div>
</div>
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Tanggal</th><th>Bahan</th><th>Tipe</th><th>Jumlah</th><th>Harga/Unit</th><th>Supplier</th><th>Catatan</th></tr></thead>
            <tbody>
                @forelse($movements as $m)
                <tr>
                    <td class="muted" style="white-space:nowrap;">{{ $m->movement_date->format('d/m/Y H:i') }}</td>
                    <td style="font-weight:600;color:#2e1d0e;">{{ $m->ingredient->name }}</td>
                    <td>@if($m->type==='in')<span class="badge badge-green">↑ Masuk</span>
                    @elseif($m->type==='out')<span class="badge badge-red">↓ Keluar</span>
                    @else<span class="badge badge-yellow">⇄ Adjust</span>@endif</td>
                    <td style="font-weight:700;color:{{ $m->type==='in'?'#166534':'#991b1b' }};">{{ $m->type==='in'?'+':'-' }}{{ number_format($m->quantity,3) }} {{ $m->ingredient->unit }}</td>
                    <td class="muted">{{ $m->cost_per_unit?'Rp '.number_format($m->cost_per_unit,0,',','.'):'—' }}</td>
                    <td class="muted">{{ $m->supplier?->name ?? '—' }}</td>
                    <td class="muted">{{ $m->notes ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:32px;color:#d4b08a;">Tidak ada data untuk periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
