<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Transaksi {{ $from }} — {{ $to }}</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: Arial, sans-serif; font-size: 12px; color: #2e1d0e; background: #fff; }
    .header { background: #5c3d1e; color: #fff; padding: 20px 24px; }
    .header h1 { font-size: 20px; margin-bottom: 4px; }
    .header p  { font-size: 11px; opacity: .8; }
    .stats { display: flex; gap: 0; border-bottom: 2px solid #f5deb3; }
    .stat  { flex: 1; padding: 14px 18px; border-right: 1px solid #f5deb3; }
    .stat:last-child { border-right: none; }
    .stat-label { font-size: 10px; color: #a06c3e; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .stat-value { font-size: 16px; font-weight: 700; color: #2e1d0e; }
    table { width: 100%; border-collapse: collapse; font-size: 11px; }
    th { background: #faf0dc; padding: 8px 12px; text-align: left; font-size: 10px; color: #a06c3e; text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid #f5deb3; }
    td { padding: 8px 12px; border-bottom: 1px solid #faf0dc; vertical-align: middle; }
    tr:hover td { background: #fdf8f0; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 10px; font-weight: 700; }
    .badge-green  { background: #dcfce7; color: #166534; }
    .badge-red    { background: #fee2e2; color: #991b1b; }
    .badge-yellow { background: #fef9c3; color: #854d0e; }
    .badge-coffee { background: #f5deb3; color: #5c3d1e; }
    .total-row td { font-weight: 700; background: #faf0dc; }
    .footer { padding: 12px 18px; background: #fdf8f0; border-top: 1px solid #f5deb3; font-size: 10px; color: #a06c3e; display: flex; justify-content: space-between; }
    @media print {
        .header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .stats, .stat, table, th, td { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="header">
    <h1>📊 Laporan Data Transaksi</h1>
    <p>MyPOS Cafe Management System &nbsp;|&nbsp; Periode: {{ $from }} s/d {{ $to }}</p>
</div>

<div class="stats">
    <div class="stat">
        <div class="stat-label">Total Transaksi</div>
        <div class="stat-value">{{ $totalTrx }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value">Rp {{ number_format($totalRevenue,0,',','.') }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Transaksi Lunas</div>
        <div class="stat-value">{{ $transactions->where('status','paid')->count() }}</div>
    </div>
    <div class="stat">
        <div class="stat-label">Rata-rata</div>
        <div class="stat-value">
            {{ $transactions->where('status','paid')->count() > 0
                ? 'Rp '.number_format($totalRevenue/$transactions->where('status','paid')->count(),0,',','.')
                : '-' }}
        </div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>No Invoice</th>
            <th>Tanggal</th>
            <th>Kasir</th>
            <th>Meja</th>
            <th>Tipe</th>
            <th>Metode</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $i => $t)
        <tr>
            <td style="color:#a06c3e;">{{ $i+1 }}</td>
            <td style="font-family:monospace;font-size:10px;color:#a06c3e;">{{ $t->invoice_number }}</td>
            <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $t->user->name }}</td>
            <td>{{ $t->table?->name ?? 'Takeaway' }}</td>
            <td>{{ $t->order_type === 'dine_in' ? 'Dine In' : 'Takeaway' }}</td>
            <td><span class="badge badge-coffee" style="text-transform:uppercase;">{{ $t->payment_method ?? '—' }}</span></td>
            <td style="font-weight:600;">Rp {{ number_format($t->total,0,',','.') }}</td>
            <td>
                @if($t->status==='paid')<span class="badge badge-green">Lunas</span>
                @elseif($t->status==='cancelled')<span class="badge badge-red">Batal</span>
                @elseif($t->status==='hold')<span class="badge badge-yellow">Hold</span>
                @else<span class="badge">Open</span>@endif
            </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:32px;color:#a06c3e;">Tidak ada data transaksi.</td></tr>
        @endforelse
        <tr class="total-row">
            <td colspan="7" style="text-align:right;">TOTAL PENDAPATAN (Lunas):</td>
            <td>Rp {{ number_format($totalRevenue,0,',','.') }}</td>
            <td></td>
        </tr>
    </tbody>
</table>

<div class="footer">
    <span>Dicetak: {{ now()->format('d M Y H:i') }}</span>
    <span>MyPOS Cafe Management System</span>
</div>

<script>window.onload = () => window.print();</script>
</body>
</html>
