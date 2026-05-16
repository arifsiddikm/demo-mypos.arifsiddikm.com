<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Laporan Inventory</title>
<style>*{font-family:Arial,sans-serif;font-size:12px;}body{padding:20px;color:#333;}h1{font-size:18px;color:#5c3d1e;margin-bottom:4px;}.sub{color:#888;margin-bottom:16px;font-size:11px;}table{width:100%;border-collapse:collapse;margin-top:14px;}th{background:#f5deb3;padding:7px 10px;text-align:left;font-size:10.5px;color:#5c3d1e;}td{padding:6px 10px;border-bottom:1px solid #faf0dc;font-size:11px;}tr:nth-child(even)td{background:#fdf8f0;}.in{color:#166534;font-weight:bold;}.out{color:#991b1b;font-weight:bold;}@media print{button{display:none;}}</style>
</head><body>
<div style="display:flex;justify-content:space-between;align-items:flex-start;">
    <div><h1>📦 Laporan Inventory</h1><p class="sub">Periode: {{ $from }} s/d {{ $to }} · Dicetak: {{ now()->format('d/m/Y H:i') }}</p></div>
    <button onclick="window.print()" style="background:#5c3d1e;color:white;border:none;padding:7px 16px;border-radius:8px;cursor:pointer;">🖨️ Print</button>
</div>
<table><thead><tr><th>Tanggal</th><th>Bahan</th><th>Tipe</th><th>Jumlah</th><th>Harga/Unit</th><th>Supplier</th><th>Catatan</th></tr></thead>
<tbody>@foreach($movements as $m)
<tr><td>{{ $m->movement_date->format('d/m/Y H:i') }}</td><td>{{ $m->ingredient->name }}</td><td class="{{ $m->type }}">{{ strtoupper($m->type) }}</td><td class="{{ $m->type==='in'?'in':'out' }}">{{ $m->type==='in'?'+':'-' }}{{ number_format($m->quantity,3) }} {{ $m->ingredient->unit }}</td><td>{{ $m->cost_per_unit?'Rp '.number_format($m->cost_per_unit,0,',','.'):'—' }}</td><td>{{ $m->supplier?->name ?? '—' }}</td><td>{{ $m->notes ?? '—' }}</td></tr>
@endforeach</tbody></table>
</body></html>
