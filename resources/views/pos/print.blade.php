<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk #{{ $transaction->invoice_number }}</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'Courier New', monospace; font-size: 12px; background: white; }
    .receipt { max-width: 300px; margin: 0 auto; padding: 16px; }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    .line { border-top: 1px dashed #ccc; margin: 8px 0; }
    .row { display: flex; justify-content: space-between; margin-bottom: 2px; }
    .total-row { font-size: 14px; font-weight: bold; }
    .no-print { margin-top: 16px; }
    @media print {
        .no-print { display: none; }
        body { margin: 0; }
    }
</style>
</head>
<body>
<div class="receipt">
    <div class="center">
        <div class="bold" style="font-size:16px">☕ {{ $settings['cafe_name'] ?? 'MyPOS Cafe' }}</div>
        @if(!empty($printer->header_text))
            @foreach(explode("\n", $printer->header_text) as $line)
            <div>{{ $line }}</div>
            @endforeach
        @endif
    </div>
    <div class="line"></div>

    <div class="row"><span>No. Invoice</span><span>{{ $transaction->invoice_number }}</span></div>
    <div class="row"><span>Kasir</span><span>{{ $transaction->user->name }}</span></div>
    <div class="row"><span>{{ $transaction->table ? $transaction->table->name : 'Takeaway' }}</span><span>{{ $transaction->paid_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</span></div>
    <div class="line"></div>

    @foreach($transaction->items as $item)
    <div style="margin-bottom:5px">
        <div class="bold">{{ $item->menu_name }}</div>
        <div class="row">
            <span>{{ $item->quantity }} x Rp {{ number_format($item->price,0,',','.') }}</span>
            <span>Rp {{ number_format($item->subtotal,0,',','.') }}</span>
        </div>
        @if($item->notes)<div style="font-style:italic;color:#666">* {{ $item->notes }}</div>@endif
    </div>
    @endforeach

    <div class="line"></div>
    <div class="row"><span>Subtotal</span><span>Rp {{ number_format($transaction->subtotal,0,',','.') }}</span></div>
    @if($transaction->tax > 0)
    <div class="row"><span>Pajak</span><span>Rp {{ number_format($transaction->tax,0,',','.') }}</span></div>
    @endif
    @if($transaction->discount > 0)
    <div class="row"><span>Diskon</span><span>- Rp {{ number_format($transaction->discount,0,',','.') }}</span></div>
    @endif
    <div class="line"></div>
    <div class="row total-row"><span>TOTAL</span><span>Rp {{ number_format($transaction->total,0,',','.') }}</span></div>
    <div class="line"></div>
    <div class="row"><span>Bayar ({{ strtoupper($transaction->payment_method) }})</span><span>Rp {{ number_format($transaction->paid_amount,0,',','.') }}</span></div>
    <div class="row"><span>Kembalian</span><span>Rp {{ number_format($transaction->change_amount,0,',','.') }}</span></div>
    <div class="line"></div>

    @if(!empty($printer->footer_text))
    <div class="center" style="margin-top:8px">
        @foreach(explode("\n", $printer->footer_text) as $line)
        <div>{{ $line }}</div>
        @endforeach
    </div>
    @else
    <div class="center" style="margin-top:8px">Terima kasih telah berkunjung!<br>Sampai jumpa lagi ☕</div>
    @endif
</div>

<div class="no-print" style="text-align:center;padding:12px">
    <button onclick="window.print()" style="background:#5c3d1e;color:white;border:none;padding:10px 24px;border-radius:8px;cursor:pointer;font-size:14px">🖨️ Print / Simpan PDF</button>
    <button onclick="window.close()" style="background:#e5e7eb;color:#374151;border:none;padding:10px 24px;border-radius:8px;cursor:pointer;font-size:14px;margin-left:8px">Tutup</button>
</div>

<script>
    // Auto-trigger print if printer_name is set
    @if($printer->printer_name && $printer->auto_print)
    window.onload = () => setTimeout(() => window.print(), 300);
    @endif
</script>
</body>
</html>
