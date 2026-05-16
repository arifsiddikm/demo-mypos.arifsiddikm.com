@extends('layouts.admin')
@section('title','Laporan')
@section('page-title','Laporan')
@section('page-subtitle','Laporan transaksi dan inventory')
@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;max-width:700px;">
    <a href="{{ route('admin.reports.transactions') }}" class="card" style="text-decoration:none;display:flex;align-items:center;gap:14px;transition:box-shadow .2s,transform .2s;" onmouseover="this.style.boxShadow='0 6px 20px rgba(92,61,30,.12)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
        <div style="width:52px;height:52px;background:#faf0dc;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;">🧾</div>
        <div><div style="font-size:14px;font-weight:700;color:#2e1d0e;margin-bottom:3px;">Laporan Transaksi</div><div style="font-size:12px;color:#a06c3e;">Riwayat penjualan, export PDF & Excel</div></div>
    </a>
    <a href="{{ route('admin.reports.inventory') }}" class="card" style="text-decoration:none;display:flex;align-items:center;gap:14px;transition:box-shadow .2s,transform .2s;" onmouseover="this.style.boxShadow='0 6px 20px rgba(92,61,30,.12)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='';this.style.transform=''">
        <div style="width:52px;height:52px;background:#dcfce7;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;">📦</div>
        <div><div style="font-size:14px;font-weight:700;color:#2e1d0e;margin-bottom:3px;">Laporan Inventory</div><div style="font-size:12px;color:#a06c3e;">Pergerakan stok bahan, export PDF & Excel</div></div>
    </a>
</div>
@endsection
