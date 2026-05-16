@extends('layouts.admin')
@section('title','Data Transaksi')
@section('page-title','Data Transaksi')
@section('page-subtitle','History lengkap semua transaksi cafe')
@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;margin-bottom:18px;">
    <div class="stat-card">
        <div style="font-size:10.5px;color:#a06c3e;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;">Total Lunas</div>
        <div style="font-size:20px;font-weight:800;color:#166534;">{{ $totalPaid }}</div>
        <div style="font-size:11px;color:#a06c3e;margin-top:2px;">transaksi</div>
    </div>
    <div class="stat-card">
        <div style="font-size:10.5px;color:#a06c3e;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;">Total Pendapatan</div>
        <div style="font-size:16px;font-weight:800;color:#5c3d1e;">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
        <div style="font-size:11px;color:#a06c3e;margin-top:2px;">dari transaksi lunas</div>
    </div>
    <div class="stat-card">
        <div style="font-size:10.5px;color:#a06c3e;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;">Dibatalkan</div>
        <div style="font-size:20px;font-weight:800;color:#991b1b;">{{ $totalCancelled }}</div>
        <div style="font-size:11px;color:#a06c3e;margin-top:2px;">transaksi</div>
    </div>
    <div class="stat-card">
        <div style="font-size:10.5px;color:#a06c3e;font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px;">Rata-rata</div>
        <div style="font-size:16px;font-weight:800;color:#5c3d1e;">
            {{ $totalPaid > 0 ? 'Rp '.number_format($totalRevenue/$totalPaid,0,',','.') : '-' }}
        </div>
        <div style="font-size:11px;color:#a06c3e;margin-top:2px;">per transaksi lunas</div>
    </div>
</div>

{{-- Filter Card --}}
<div class="card" style="margin-bottom:16px;padding:16px 20px;">
    <form method="GET" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:10px;">
        <div>
            <label class="form-label" style="font-size:11px;">Dari Tanggal</label>
            <input type="date" name="from" value="{{ $from }}" class="form-input" style="width:140px;">
        </div>
        <div>
            <label class="form-label" style="font-size:11px;">Sampai</label>
            <input type="date" name="to" value="{{ $to }}" class="form-input" style="width:140px;">
        </div>
        <div>
            <label class="form-label" style="font-size:11px;">Status</label>
            <select name="status" class="form-input" style="width:130px;">
                <option value="all"       {{ $status==='all'?'selected':'' }}>Semua Status</option>
                <option value="paid"      {{ $status==='paid'?'selected':'' }}>✅ Lunas</option>
                <option value="cancelled" {{ $status==='cancelled'?'selected':'' }}>❌ Dibatalkan</option>
                <option value="hold"      {{ $status==='hold'?'selected':'' }}>⏸️ Hold</option>
                <option value="open"      {{ $status==='open'?'selected':'' }}>🔓 Open</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size:11px;">Kasir</label>
            <select name="kasir" class="form-input" style="width:150px;">
                <option value="all">Semua Kasir</option>
                @foreach($kasirList as $u)
                <option value="{{ $u->id }}" {{ $kasir==$u->id?'selected':'' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size:11px;">Metode Bayar</label>
            <select name="payment" class="form-input" style="width:130px;">
                <option value="all"     {{ $payment==='all'?'selected':'' }}>Semua</option>
                <option value="cash"    {{ $payment==='cash'?'selected':'' }}>💵 Cash</option>
                <option value="transfer"{{ $payment==='transfer'?'selected':'' }}>🏦 Transfer</option>
                <option value="qris"    {{ $payment==='qris'?'selected':'' }}>📱 QRIS</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size:11px;">Tipe Order</label>
            <select name="type" class="form-input" style="width:130px;">
                <option value="all"     {{ $type==='all'?'selected':'' }}>Semua</option>
                <option value="dine_in" {{ $type==='dine_in'?'selected':'' }}>🪑 Dine In</option>
                <option value="takeaway"{{ $type==='takeaway'?'selected':'' }}>🥡 Takeaway</option>
            </select>
        </div>
        <div>
            <label class="form-label" style="font-size:11px;">No. Invoice</label>
            <input type="text" name="search" value="{{ $search }}" class="form-input" style="width:160px;" placeholder="Cari invoice...">
        </div>
        <div style="display:flex;gap:6px;align-items:flex-end;">
            <button type="submit" class="btn btn-primary btn-sm">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:13px;height:13px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                Filter
            </button>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-ghost btn-sm">Reset</a>
        </div>
    </form>
</div>

{{-- Table + Export --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:12px 16px;border-bottom:1px solid #f5deb3;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="font-size:12.5px;font-weight:600;color:#5c3d1e;">
            📋 Menampilkan {{ $transactions->total() }} transaksi
        </div>
        <div style="display:flex;gap:6px;">
            <a href="{{ route('admin.transactions.pdf', request()->all()) }}" target="_blank"
                class="btn btn-ghost btn-sm" style="border-color:#fecaca;color:#991b1b;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                📄 PDF
            </a>
            <a href="{{ route('admin.transactions.excel', request()->all()) }}"
                class="btn btn-ghost btn-sm" style="border-color:#bbf7d0;color:#166534;">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:12px;height:12px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                📊 Export XLSX
            </a>
        </div>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Meja</th>
                <th>Tipe</th>
                <th>Metode</th>
                <th>Item</th>
                <th>Total</th>
                <th>Status</th>
                <th></th>
            </tr></thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td style="font-family:monospace;font-size:11px;color:#a06c3e;white-space:nowrap;">{{ $t->invoice_number }}</td>
                    <td class="muted" style="white-space:nowrap;">
                        <div>{{ $t->created_at->format('d M Y') }}</div>
                        <div style="font-size:10.5px;">{{ $t->created_at->format('H:i') }}</div>
                    </td>
                    <td style="font-size:12.5px;">{{ $t->user->name }}</td>
                    <td class="muted">{{ $t->table?->name ?? '🥡 Takeaway' }}</td>
                    <td>
                        @if($t->order_type==='dine_in')
                        <span class="badge badge-blue">Dine In</span>
                        @else
                        <span class="badge badge-orange">Takeaway</span>
                        @endif
                    </td>
                    <td>
                        @if($t->payment_method)
                        <span class="badge badge-coffee" style="text-transform:uppercase;">{{ $t->payment_method }}</span>
                        @else<span class="muted">—</span>@endif
                    </td>
                    <td class="muted">{{ $t->items->sum('quantity') }} item</td>
                    <td style="font-weight:700;color:#5c3d1e;white-space:nowrap;">Rp {{ number_format($t->total,0,',','.') }}</td>
                    <td>
                        @switch($t->status)
                            @case('paid')<span class="badge badge-green">✅ Lunas</span>@break
                            @case('cancelled')<span class="badge badge-red">❌ Batal</span>@break
                            @case('hold')<span class="badge badge-yellow">⏸️ Hold</span>@break
                            @default<span class="badge badge-blue">🔓 Open</span>
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('admin.transactions.show', $t) }}" class="btn btn-secondary btn-xs">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" style="text-align:center;padding:48px;color:#d4b08a;">
                    <span style="font-size:32px;display:block;margin-bottom:8px;">🧾</span>
                    Tidak ada transaksi untuk filter ini.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($transactions->hasPages())
    <div style="padding:14px 16px;border-top:1px solid #f5deb3;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <div style="font-size:12px;color:#a06c3e;">
            Halaman {{ $transactions->currentPage() }} dari {{ $transactions->lastPage() }}
            ({{ $transactions->total() }} total)
        </div>
        <div style="display:flex;gap:4px;">
            @if($transactions->onFirstPage())
            <span class="btn btn-ghost btn-xs" style="opacity:.4;cursor:not-allowed;">‹ Prev</span>
            @else
            <a href="{{ $transactions->previousPageUrl() }}" class="btn btn-ghost btn-xs">‹ Prev</a>
            @endif

            @foreach($transactions->getUrlRange(max(1,$transactions->currentPage()-2), min($transactions->lastPage(),$transactions->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="btn btn-xs {{ $page==$transactions->currentPage() ? 'btn-primary' : 'btn-ghost' }}">{{ $page }}</a>
            @endforeach

            @if($transactions->hasMorePages())
            <a href="{{ $transactions->nextPageUrl() }}" class="btn btn-ghost btn-xs">Next ›</a>
            @else
            <span class="btn btn-ghost btn-xs" style="opacity:.4;cursor:not-allowed;">Next ›</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
