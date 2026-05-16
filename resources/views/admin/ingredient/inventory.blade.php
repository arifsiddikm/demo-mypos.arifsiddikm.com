@extends('layouts.admin')
@section('title','Inventory')
@section('page-title','Inventory Bahan')
@section('page-subtitle','Riwayat pergerakan stok bahan')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <span style="font-size:12px;color:#a06c3e;">{{ $movements->total() }} catatan</span>
    <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Input Stok
    </a>
</div>
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Tanggal</th><th>Bahan</th><th>Tipe</th><th>Jumlah</th><th>Harga/Unit</th><th>Supplier</th><th>Catatan</th><th>Oleh</th></tr></thead>
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
                    <td class="muted">{{ $m->user->name }}</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:40px;color:#d4b08a;">Belum ada catatan stok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movements->hasPages())
    <div style="padding:12px 20px;border-top:1px solid #f5deb3;">{{ $movements->links() }}</div>
    @endif
</div>
@endsection
