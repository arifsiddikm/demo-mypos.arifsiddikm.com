@extends('layouts.admin')
@section('title','Pengaturan Printer')
@section('page-title','Pengaturan Printer')
@section('page-subtitle','Konfigurasi printer untuk cetak struk')
@section('content')
<div style="max-width:560px;"><div class="card">
<form method="POST" action="{{ route('admin.settings.printer.update') }}">
    @csrf
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div><label class="form-label">Nama Printer</label>
        <input type="text" name="printer_name" value="{{ old('printer_name',$printer->printer_name) }}" class="form-input" placeholder="Kosong = tidak ada printer (cetak PDF)">
        <p class="form-hint">Jika dikosongkan, struk akan dicetak sebagai PDF preview</p></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div><label class="form-label">Tipe Printer</label>
            <select name="printer_type" class="form-input">
                <option value="thermal" {{ $printer->printer_type==='thermal'?'selected':'' }}>Thermal (58mm/80mm)</option>
                <option value="laser" {{ $printer->printer_type==='laser'?'selected':'' }}>Laser/Inkjet (A4)</option>
            </select></div>
            <div><label class="form-label">Ukuran Kertas</label>
            <select name="paper_size" class="form-input">
                @foreach(['58mm','80mm','A4'] as $s)
                <option value="{{ $s }}" {{ $printer->paper_size===$s?'selected':'' }}>{{ $s }}</option>@endforeach
            </select></div>
        </div>
        <label class="toggle-wrap" style="cursor:pointer;">
            <div class="toggle-switch">
                <input type="hidden" name="auto_print" value="0">
                <input type="checkbox" name="auto_print" value="1" {{ $printer->auto_print?'checked':'' }}>
                <span class="toggle-track"></span>
            </div>
            <span class="toggle-label">Cetak otomatis setelah transaksi</span>
        </label>
        <div><label class="form-label">Header Struk</label>
        <textarea name="header_text" rows="4" class="form-input" style="font-family:monospace;font-size:12px;">{{ old('header_text',$printer->header_text) }}</textarea>
        <p class="form-hint">Tampil di bagian atas struk</p></div>
        <div><label class="form-label">Footer Struk</label>
        <textarea name="footer_text" rows="3" class="form-input" style="font-family:monospace;font-size:12px;">{{ old('footer_text',$printer->footer_text) }}</textarea></div>
    </div>
    <div style="display:flex;gap:8px;margin-top:20px;padding-top:16px;border-top:1px solid #f5deb3;">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-ghost">Kembali</a>
    </div>
</form>
</div></div>
@endsection
