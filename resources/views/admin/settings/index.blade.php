@extends('layouts.admin')
@section('title','Pengaturan')
@section('page-title','Pengaturan Cafe')
@section('page-subtitle','Konfigurasi informasi dan sistem cafe')
@section('content')
<div style="display:grid;grid-template-columns:1fr 280px;gap:16px;">
    <div class="card">
        <div style="font-size:13px;font-weight:700;color:#2e1d0e;margin-bottom:16px;">🏪 Informasi Cafe</div>
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div><label class="form-label">Nama Cafe</label><input type="text" name="cafe_name" value="{{ $settings['cafe_name'] ?? '' }}" class="form-input"></div>
                <div><label class="form-label">Tagline</label><input type="text" name="cafe_tagline" value="{{ $settings['cafe_tagline'] ?? '' }}" class="form-input"></div>
                <div><label class="form-label">Telepon</label><input type="text" name="cafe_phone" value="{{ $settings['cafe_phone'] ?? '' }}" class="form-input"></div>
                <div><label class="form-label">Email</label><input type="email" name="cafe_email" value="{{ $settings['cafe_email'] ?? '' }}" class="form-input"></div>
                <div style="grid-column:1/-1;"><label class="form-label">Alamat</label><input type="text" name="cafe_address" value="{{ $settings['cafe_address'] ?? '' }}" class="form-input"></div>
                <div style="grid-column:1/-1;"><label class="form-label">Deskripsi</label><textarea name="cafe_description" rows="3" class="form-input">{{ $settings['cafe_description'] ?? '' }}</textarea></div>
                <div><label class="form-label">Pajak (%)</label><input type="number" name="tax_percentage" value="{{ $settings['tax_percentage'] ?? '0' }}" class="form-input" min="0" max="100" step="0.1"></div>
            </div>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f5deb3;">
                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
    <div style="display:flex;flex-direction:column;gap:12px;">
        <a href="{{ route('admin.settings.printer') }}" class="card" style="text-decoration:none;display:flex;align-items:center;gap:12px;transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 4px 14px rgba(92,61,30,.1)'" onmouseout="this.style.boxShadow=''">
            <span style="font-size:28px;">🖨️</span>
            <div><div style="font-size:13px;font-weight:600;color:#2e1d0e;">Pengaturan Printer</div><div style="font-size:11px;color:#a06c3e;">Konfigurasi printer & struk</div></div>
            <svg style="margin-left:auto;width:14px;height:14px;color:#d4b08a;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
        <div class="card" style="background:#fdf8f0;">
            <div style="font-size:12px;font-weight:700;color:#5c3d1e;margin-bottom:10px;">Akun Demo</div>
            <div style="display:flex;flex-direction:column;gap:6px;">
                <div style="background:#fff;border-radius:9px;padding:10px 12px;border:1px solid #f5deb3;">
                    <div style="font-size:12px;font-weight:600;color:#2e1d0e;">Admin</div>
                    <div style="font-size:11px;color:#a06c3e;">admin@mypos.com / password</div>
                </div>
                <div style="background:#fff;border-radius:9px;padding:10px 12px;border:1px solid #f5deb3;">
                    <div style="font-size:12px;font-weight:600;color:#2e1d0e;">Kasir</div>
                    <div style="font-size:11px;color:#a06c3e;">kasir@mypos.com / password</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
