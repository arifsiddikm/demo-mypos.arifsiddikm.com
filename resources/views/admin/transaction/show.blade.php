@extends('layouts.admin')
@section('title','Detail Transaksi #'.$transaction->invoice_number)
@section('page-title','Detail Transaksi')
@section('page-subtitle','#{{ $transaction->invoice_number }}')
@section('content')

<div style="display:flex;gap:8px;margin-bottom:16px;">
    <a href="{{ route('admin.transactions.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
    <a href="{{ route('pos.transaction.print', $transaction) }}" target="_blank" class="btn btn-secondary btn-sm">🖨️ Print Struk</a>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:16px;align-items:start;">
    {{-- Left: Items --}}
    <div>
        <div class="card" style="padding:0;overflow:hidden;margin-bottom:14px;">
            <div style="padding:14px 18px;border-bottom:1px solid #f5deb3;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-weight:700;color:#2e1d0e;">Item Pesanan</span>
                <span class="badge badge-coffee">{{ $transaction->items->count() }} item</span>
            </div>
            <table class="data-table">
                <thead><tr><th>Menu</th><th>Kategori</th><th>Harga</th><th>Qty</th><th style="text-align:right">Subtotal</th></tr></thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr>
                        <td style="font-weight:600;">{{ $item->menu_name }}</td>
                        <td class="muted">{{ $item->menu?->category?->name ?? '—' }}</td>
                        <td class="muted">Rp {{ number_format($item->price,0,',','.') }}</td>
                        <td><span class="badge badge-blue">{{ $item->quantity }}x</span></td>
                        <td style="text-align:right;font-weight:600;">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                    </tr>
                    @if($item->notes)
                    <tr><td colspan="5" style="padding:4px 16px 8px;font-size:11px;color:#a06c3e;">📝 {{ $item->notes }}</td></tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($transaction->notes)
        <div class="card" style="padding:14px 18px;">
            <div style="font-size:12px;font-weight:700;color:#a06c3e;margin-bottom:6px;">📝 CATATAN</div>
            <div style="font-size:13px;color:#2e1d0e;">{{ $transaction->notes }}</div>
        </div>
        @endif
    </div>

    {{-- Right: Summary --}}
    <div>
        <div class="card" style="margin-bottom:12px;">
            <div style="font-weight:700;color:#2e1d0e;font-size:13px;margin-bottom:14px;">Info Transaksi</div>

            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">No Invoice</span>
                    <span style="font-family:monospace;font-weight:600;color:#5c3d1e;">{{ $transaction->invoice_number }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Tanggal</span>
                    <span style="font-weight:600;">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Kasir</span>
                    <span style="font-weight:600;">{{ $transaction->user->name }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Meja</span>
                    <span>{{ $transaction->table?->name ?? '🥡 Takeaway' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Tipe Order</span>
                    <span class="badge {{ $transaction->order_type==='dine_in'?'badge-blue':'badge-orange' }}">
                        {{ $transaction->order_type==='dine_in' ? 'Dine In' : 'Takeaway' }}
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Metode Bayar</span>
                    <span class="badge badge-coffee" style="text-transform:uppercase;">{{ $transaction->payment_method ?? '—' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Status</span>
                    @switch($transaction->status)
                        @case('paid')<span class="badge badge-green">✅ Lunas</span>@break
                        @case('cancelled')<span class="badge badge-red">❌ Batal</span>@break
                        @case('hold')<span class="badge badge-yellow">⏸️ Hold</span>@break
                        @default<span class="badge badge-blue">🔓 Open</span>
                    @endswitch
                </div>
            </div>

            <div class="divider"></div>

            <div style="display:flex;flex-direction:column;gap:7px;font-size:13px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Subtotal</span>
                    <span>Rp {{ number_format($transaction->subtotal,0,',','.') }}</span>
                </div>
                @if($transaction->tax > 0)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Pajak</span>
                    <span>Rp {{ number_format($transaction->tax,0,',','.') }}</span>
                </div>
                @endif
                @if($transaction->discount > 0)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Diskon</span>
                    <span style="color:#16a34a;">- Rp {{ number_format($transaction->discount,0,',','.') }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:2px solid #f5deb3;margin-top:4px;">
                    <span style="font-weight:700;font-size:15px;">TOTAL</span>
                    <span style="font-weight:800;font-size:16px;color:#5c3d1e;">Rp {{ number_format($transaction->total,0,',','.') }}</span>
                </div>
                @if($transaction->paid_amount > 0)
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Dibayar</span>
                    <span>Rp {{ number_format($transaction->paid_amount,0,',','.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:#a06c3e;">Kembalian</span>
                    <span style="font-weight:600;color:#16a34a;">Rp {{ number_format($transaction->change_amount,0,',','.') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
