@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-subtitle','Ringkasan aktivitas cafe hari ini')

@section('content')
{{-- Stat grid --}}
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px;margin-bottom:20px;">
    @php $stats=[
        ['💰','Penjualan Hari Ini','Rp '.number_format($todaySales,0,',','.'),'#f0fdf4','#166534'],
        ['📈','Bulan Ini','Rp '.number_format($monthSales,0,',','.'),'#dbeafe','#1e40af'],
        ['🧾','Transaksi Hari Ini',$todayTrx.' trx','#faf5ff','#6b21a8'],
        ['⏳','Order Aktif',$openOrders.' order','#fefce8','#854d0e'],
        ['☕','Menu Aktif',$totalMenus.' item','#fff7ed','#9a3412'],
        ['⚠️','Stok Rendah',$lowStock.' bahan','#fef2f2','#991b1b'],
    ]; @endphp
    @foreach($stats as $s)
    <div class="stat-card" style="border-left:3px solid {{ $s[4] }};">
        <div style="font-size:20px;margin-bottom:8px;">{{ $s[0] }}</div>
        <div style="font-size:11px;color:#a06c3e;font-weight:600;margin-bottom:3px;">{{ $s[1] }}</div>
        <div style="font-size:16px;font-weight:700;color:{{ $s[4] }};">{{ $s[2] }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:16px;margin-bottom:16px;">
    <div class="card">
        <div style="font-size:13px;font-weight:700;color:#2e1d0e;margin-bottom:14px;">📊 Penjualan 7 Hari Terakhir</div>
        <canvas id="salesChart" height="180"></canvas>
    </div>
    <div class="card">
        <div style="font-size:13px;font-weight:700;color:#2e1d0e;margin-bottom:14px;">🏆 Menu Terlaris</div>
        <div style="display:flex;flex-direction:column;gap:9px;">
            @forelse($topMenus as $i=>$m)
            <div style="display:flex;align-items:center;gap:9px;">
                <span style="width:20px;height:20px;border-radius:50%;background:{{ ['#f59e0b','#9ca3af','#d97706','#f5deb3'][$i] ?? '#f5deb3' }};color:{{ $i<3?'#fff':'#5c3d1e' }};display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">{{ $i+1 }}</span>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:12px;font-weight:600;color:#2e1d0e;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $m->menu_name }}</div>
                    <div style="font-size:10.5px;color:#a06c3e;">{{ $m->total_qty }} pcs</div>
                </div>
                <div style="font-size:11px;font-weight:700;color:#5c3d1e;white-space:nowrap;">Rp {{ number_format($m->total_revenue/1000,0) }}k</div>
            </div>
            @empty
            <p style="color:#d4b08a;font-size:12px;text-align:center;padding:16px 0;">Belum ada data</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px;">
    @foreach([
        [route('pos.index'),'🖥️','Buka POS','#5c3d1e','#fff','0 3px 10px rgba(92,61,30,.3)'],
        [route('admin.menus.create'),'➕','Tambah Menu','#fff','#5c3d1e','none'],
        [route('admin.inventory.create'),'📦','Input Stok','#fff','#5c3d1e','none'],
        [route('admin.reports.transactions'),'📋','Laporan','#fff','#5c3d1e','none'],
    ] as $q)
    <a href="{{ $q[0] }}" style="background:{{ $q[3] }};color:{{ $q[4] }};border:1.5px solid #f5deb3;border-radius:13px;padding:18px 12px;display:flex;flex-direction:column;align-items:center;gap:7px;text-decoration:none;box-shadow:{{ $q[5] }};transition:transform .2s,box-shadow .2s;"
       onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
        <span style="font-size:22px;">{{ $q[1] }}</span>
        <span style="font-size:12px;font-weight:600;">{{ $q[2] }}</span>
    </a>
    @endforeach
</div>

{{-- Recent transactions --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="padding:16px 20px;border-bottom:1px solid #f5deb3;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:13px;font-weight:700;color:#2e1d0e;">🧾 Transaksi Terbaru</span>
        <a href="{{ route('admin.reports.transactions') }}" class="btn btn-ghost btn-xs">Lihat Semua →</a>
    </div>
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr>
                <th>Invoice</th><th>Kasir</th><th>Meja</th><th>Total</th><th>Status</th><th>Waktu</th>
            </tr></thead>
            <tbody>
                @forelse($recentTransactions as $t)
                <tr>
                    <td style="font-family:monospace;font-size:11px;color:#a06c3e;">{{ $t->invoice_number }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td class="muted">{{ $t->table?->name ?? 'Takeaway' }}</td>
                    <td style="font-weight:600;">Rp {{ number_format($t->total,0,',','.') }}</td>
                    <td>
                        @switch($t->status)
                            @case('paid')    <span class="badge badge-green">Lunas</span>  @break
                            @case('open')    <span class="badge badge-blue">Buka</span>    @break
                            @case('hold')    <span class="badge badge-yellow">Hold</span>  @break
                            @default         <span class="badge badge-red">Batal</span>
                        @endswitch
                    </td>
                    <td class="muted">{{ $t->created_at->format('d/m H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:#d4b08a;">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('salesChart').getContext('2d'), {
    type:'bar',
    data:{
        labels: {!! json_encode(array_column($salesChart,'date')) !!},
        datasets:[{ label:'Penjualan', data:{!! json_encode(array_column($salesChart,'total')) !!},
            backgroundColor:'rgba(92,61,30,.1)', borderColor:'#5c3d1e',
            borderWidth:2, borderRadius:6, borderSkipped:false }]
    },
    options:{ responsive:true, plugins:{legend:{display:false}},
        scales:{
            y:{grid:{color:'#faf0dc'},ticks:{callback:v=>'Rp '+(v/1000).toFixed(0)+'k',color:'#a06c3e',font:{size:10}}},
            x:{grid:{display:false},ticks:{color:'#a06c3e',font:{size:10}}}
        }
    }
});
</script>
@endsection
