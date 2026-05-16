@extends('layouts.admin')
@section('title','Kategori')
@section('page-title','Kategori Menu')
@section('page-subtitle','Kelola kategori untuk pengelompokan menu')
@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:16px;">
    <div class="card">
        <div style="font-size:13px;font-weight:700;color:#2e1d0e;margin-bottom:16px;">Tambah Kategori</div>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <label class="form-label">Nama <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Minuman" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" class="form-input" placeholder="☕" maxlength="10">
                </div>
                <div>
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order',0) }}" class="form-input" min="0">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Tambah Kategori</button>
            </div>
        </form>
    </div>

    <div class="card" style="padding:0;overflow:hidden;">
        <div style="padding:16px 20px;border-bottom:1px solid #f5deb3;font-size:13px;font-weight:700;color:#2e1d0e;">Daftar Kategori</div>
        <div>
            @forelse($categories as $cat)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid #fdf8f0;transition:background .1s;" onmouseover="this.style.background='#fdf8f0'" onmouseout="this.style.background=''">
                <span style="font-size:22px;width:36px;text-align:center;flex-shrink:0;">{{ $cat->icon ?? '📂' }}</span>
                <div style="flex:1;">
                    <div style="font-size:13px;font-weight:600;color:#2e1d0e;">{{ $cat->name }}</div>
                    <div style="font-size:11px;color:#a06c3e;">{{ $cat->menus_count }} menu · slug: {{ $cat->slug }}</div>
                </div>
                @if($cat->is_active)<span class="badge badge-green">Aktif</span>
                @else<span class="badge badge-gray">Nonaktif</span>@endif
                @if($cat->slug !== 'all')
                <div style="display:flex;gap:6px;">
                    <button onclick="editCat({{ $cat->id }},'{{ addslashes($cat->name) }}','{{ $cat->icon }}',{{ $cat->sort_order }},{{ $cat->is_active?1:0 }})"
                        class="btn btn-secondary btn-xs">Edit</button>
                    <form method="POST" action="{{ route('admin.categories.destroy',$cat) }}" style="margin:0">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-danger btn-xs" data-confirm="Hapus kategori {{ $cat->name }}?">Hapus</button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div style="text-align:center;padding:40px;color:#d4b08a;font-size:13px;">Belum ada kategori</div>
            @endforelse
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title">Edit Kategori</span>
            <button type="button" class="btn-modal-close" onclick="closeModal()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" id="edit-form">
            @csrf @method('PUT')
            <div class="modal-body" style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" id="edit-name" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Icon</label>
                    <input type="text" name="icon" id="edit-icon" class="form-input" maxlength="10">
                </div>
                <div>
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" id="edit-sort" class="form-input" min="0">
                </div>
                <label class="form-check">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="edit-active" value="1" class="form-check-input">
                    <span class="form-check-label">Aktif</span>
                </label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function editCat(id,name,icon,sort,active){
    document.getElementById('edit-form').action='/admin/categories/'+id;
    document.getElementById('edit-name').value=name;
    document.getElementById('edit-icon').value=icon;
    document.getElementById('edit-sort').value=sort;
    document.getElementById('edit-active').checked=active==1;
    document.getElementById('edit-modal').classList.remove('hidden');
}
function closeModal(){document.getElementById('edit-modal').classList.add('hidden');}
</script>
@endsection
