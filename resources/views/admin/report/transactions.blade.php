@extends('layouts.admin')
@section('title','Laporan Transaksi')
@section('page-title','Laporan Transaksi')
@section('page-subtitle','Filter dan export laporan penjualan')
@section('content')
<div class="card" style="margin-bottom:16px;">
    <form method="GET" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px;">
        <div><label class="form-label">Dari</label><input type="date" name="from" value="{{ $from }}" class="form-input" style="width:auto;"></div>
        <div><label class="form-label">Sampai</label><input type="date" name="to" value="{{ $to }}" class="form-input" style="width:auto;"></div>
        <div><label class="form-label">Status</label>
        <select name="status" class="form-input" style="width:auto;">
            <option value="all" {{ $status==='all'?'selected':'' }}>Semua</option>
            <option value="paid" {{ $status==='paid'?'selected':'' }}>Lunas</option>
            <option value="cancelled" {{ $status==='cancelled'?'selected':'' }}>Dibatalkan</option>
        </select></div>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('admin.reports.transactions.pdf',request()->all()) }}" target="_blank" class="btn btn-ghost">📄 PDF</a>
        <a href="{{ route('admin.reports.transactions.excel',request()->all()) }}" class="btn btn-ghost">📊 Excel</a>
    </form>
</div>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:10px;margin-bottom:16px;">
    @foreach([['Total Transaksi',$totalTrx.' trx','#dbeafe','#1e40af'],['Total Pendapatan','Rp '.number_format($totalRevenue,0,',','.'),'#dcfce7','#166534'],['Rata-rata/Trx',$totalTrx>0?'Rp '.number_format($totalRevenue/$totalTrx,0,',','.'):'-','#fef9c3','#854d0e'],['Periode',$from.' s/d '.$to,'#fdf8f0','#5c3d1e']] as $s)
    <div style="background:{{ $s[2] }};border-radius:12px;padding:14px 16px;">
        <div style="font-size:11px;color:#a06c3e;font-weight:600;margin-bottom:4px;">{{ $s[0] }}</div>
        <div style="font-size:14px;font-weight:700;color:{{ $s[3] }};">{{ $s[1] }}</div>
    </div>
    @endforeach
</div>
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Invoice</th><th>Kasir</th><th>Meja</th><th>Tipe</th><th>Bayar</th><th>Total</th><th>Status</th><th>Waktu</th></tr></thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td style="font-family:monospace;font-size:11px;color:#a06c3e;">{{ $t->invoice_number }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td class="muted">{{ $t->table?->name ?? 'Takeaway' }}</td>
                    <td class="muted" style="text-transform:capitalize;">{{ str_replace('_',' ',$t->order_type) }}</td>
                    <td><span class="badge badge-coffee" style="text-transform:uppercase;">{{ $t->payment_method ?? '—' }}</span></td>
                    <td style="font-weight:600;">Rp {{ number_format($t->total,0,',','.') }}</td>
                    <td>@switch($t->status)
                        @case('paid')<span class="badge badge-green">Lunas</span>@break
                        @case('cancelled')<span class="badge badge-red">Batal</span>@break
                        @case('hold')<span class="badge badge-yellow">Hold</span>@break
                        @default<span class="badge badge-blue">Buka</span>
                    @endswitch</td>
                    <td class="muted">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:32px;color:#d4b08a;">Tidak ada data untuk periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
