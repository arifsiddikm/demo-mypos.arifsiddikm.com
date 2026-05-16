<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Laporan Transaksi</title>
<style>*{font-family:Arial,sans-serif;font-size:12px;}body{padding:20px;color:#333;}h1{font-size:18px;color:#5c3d1e;margin-bottom:4px;}.sub{color:#888;margin-bottom:16px;font-size:11px;}table{width:100%;border-collapse:collapse;margin-top:14px;}th{background:#f5deb3;padding:7px 10px;text-align:left;font-size:10.5px;color:#5c3d1e;}td{padding:6px 10px;border-bottom:1px solid #faf0dc;font-size:11px;}tr:nth-child(even)td{background:#fdf8f0;}.total-row td{font-weight:bold;background:#f5deb3;border-top:2px solid #d4b08a;}.badge{display:inline-block;padding:1px 7px;border-radius:20px;font-size:10px;}.paid{background:#dcfce7;color:#166534;}.cancelled{background:#fee2e2;color:#991b1b;}@media print{button{display:none;}}</style>
</head><body>
<div style="display:flex;justify-content:space-between;align-items:flex-start;">
    <div><h1>☕ Laporan Transaksi</h1><p class="sub">Periode: {{ $from }} s/d {{ $to }} · Dicetak: {{ now()->format('d/m/Y H:i') }}</p></div>
    <button onclick="window.print()" style="background:#5c3d1e;color:white;border:none;padding:7px 16px;border-radius:8px;cursor:pointer;">🖨️ Print</button>
</div>
<div style="background:#f5deb3;padding:10px 14px;border-radius:8px;margin-bottom:14px;display:flex;gap:40px;">
    <div><strong>Total:</strong> {{ $transactions->count() }} transaksi</div>
    <div><strong>Pendapatan:</strong> Rp {{ number_format($totalRevenue,0,',','.') }}</div>
</div>
<table><thead><tr><th>Invoice</th><th>Kasir</th><th>Meja</th><th>Pembayaran</th><th>Total</th><th>Status</th><th>Waktu</th></tr></thead>
<tbody>@foreach($transactions as $t)
<tr><td style="font-family:monospace">{{ $t->invoice_number }}</td><td>{{ $t->user->name }}</td><td>{{ $t->table?->name ?? 'Takeaway' }}</td><td style="text-transform:uppercase">{{ $t->payment_method ?? '—' }}</td><td><strong>Rp {{ number_format($t->total,0,',','.') }}</strong></td><td><span class="badge {{ $t->status }}">{{ ucfirst($t->status) }}</span></td><td>{{ $t->created_at->format('d/m/Y H:i') }}</td></tr>
@endforeach
<tr class="total-row"><td colspan="4">TOTAL PENDAPATAN</td><td>Rp {{ number_format($totalRevenue,0,',','.') }}</td><td colspan="2"></td></tr>
</tbody></table>
</body></html>
