<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — MyPOS</title>
    <meta name="description" content="MyPOS — Sistem kasir modern untuk cafe Anda">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream:  { 50:'#fdf8f0',100:'#faf0dc',200:'#f5deb3',300:'#e8c88a',400:'#d4a864',500:'#b8894a',600:'#9a6e35',700:'#7d5628',800:'#5c3d1e',900:'#3d2810' },
                        coffee: { 50:'#f5ede3',100:'#e8d5ba',200:'#d4b08a',300:'#bc8a5c',400:'#a06c3e',500:'#7d5228',600:'#5e3d1e',700:'#472e16',800:'#2e1d0e',900:'#1a0f07' },
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fdf8f0; color: #2e1d0e; }
        a { text-decoration: none; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #fdf8f0; }
        ::-webkit-scrollbar-thumb { background: #d4b08a; border-radius: 3px; }

        .app-wrap   { display: flex; height: 100vh; overflow: hidden; }
        .main-wrap  { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }
        .main-content { flex: 1; overflow-y: auto; padding: 20px 24px 32px; }

        .sidebar {
            width: 240px; min-width: 240px;
            background: #ffffff;
            border-right: 1px solid #f5deb3;
            display: flex; flex-direction: column;
            height: 100vh; overflow: hidden;
            box-shadow: 2px 0 12px rgba(92,61,30,.07);
        }
        .sidebar-logo {
            display: flex; align-items: center; gap: 11px;
            padding: 18px 16px 15px;
            border-bottom: 1px solid #f5deb3;
            text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 38px; height: 38px; border-radius: 11px;
            background: #5c3d1e;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 3px 8px rgba(92,61,30,.35); flex-shrink: 0;
        }
        .sidebar-logo-icon svg { width: 20px; height: 20px; }
        .sidebar-logo-text strong { display: block; font-size: 15px; font-weight: 700; color: #2e1d0e; line-height: 1.2; }
        .sidebar-logo-text small  { font-size: 10.5px; color: #a06c3e; letter-spacing: .01em; }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 10px 10px; }
        .sidebar-section {
            font-size: 9.5px; font-weight: 700; letter-spacing: .09em;
            text-transform: uppercase; color: #d4b08a;
            padding: 12px 10px 5px; display: block;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 10px; border-radius: 9px; margin-bottom: 1px;
            font-size: 13px; font-weight: 500; color: #5c3d1e;
            text-decoration: none; transition: background .15s, color .15s;
        }
        .sidebar-link svg { width: 15px; height: 15px; flex-shrink: 0; opacity: .75; }
        .sidebar-link:hover { background: #faf0dc; color: #2e1d0e; }
        .sidebar-link:hover svg { opacity: 1; }
        .sidebar-link.active {
            background: #5c3d1e; color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(92,61,30,.28);
        }
        .sidebar-link.active svg { opacity: 1; }
        /* Kasir badge di sidebar — label kecil "View Only" */
        .sidebar-link .badge-ro {
            margin-left: auto; font-size: 9px; padding: 1px 6px;
            background: #fef9c3; color: #854d0e; border-radius: 99px;
            font-weight: 700; letter-spacing: .03em;
        }

        .sidebar-user {
            padding: 12px 14px;
            border-top: 1px solid #f5deb3;
            display: flex; align-items: center; gap: 9px;
            background: #fdf8f0;
        }
        .sidebar-avatar {
            width: 33px; height: 33px; border-radius: 50%;
            background: #f5deb3; color: #5c3d1e;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; flex-shrink: 0;
        }
        /* Kasir avatar warna beda */
        .sidebar-avatar.kasir { background: #dbeafe; color: #1e40af; }
        .sidebar-user-name { font-size: 12.5px; font-weight: 600; color: #2e1d0e; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-role { font-size: 10.5px; color: #a06c3e; text-transform: capitalize; }
        .btn-logout {
            margin-left: auto; padding: 5px; background: none; border: none;
            color: #d4b08a; border-radius: 7px; cursor: pointer; transition: all .15s;
            display: flex; align-items: center; flex-shrink: 0;
        }
        .btn-logout:hover { background: #fee2e2; color: #ef4444; }
        .btn-logout svg { width: 15px; height: 15px; }

        .topbar {
            background: #ffffff; border-bottom: 1px solid #f5deb3;
            padding: 13px 24px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 4px rgba(92,61,30,.06); flex-shrink: 0;
        }
        .topbar-title { font-size: 15px; font-weight: 700; color: #2e1d0e; }
        .topbar-sub   { font-size: 11.5px; color: #a06c3e; margin-top: 1px; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-pill  {
            font-size: 11.5px; color: #a06c3e; display: flex; align-items: center;
            gap: 4px; text-decoration: none; transition: color .15s;
        }
        .topbar-pill:hover { color: #5c3d1e; }
        .topbar-time  { font-size: 11.5px; color: #d4b08a; font-weight: 500; }
        .topbar-sep   { color: #f5deb3; }
        /* Kasir banner */
        .kasir-banner {
            background: #eff6ff; border-bottom: 1px solid #bfdbfe;
            padding: 7px 24px; font-size: 12px; color: #1e40af;
            display: flex; align-items: center; gap: 6px;
        }

        .alerts-wrap { padding: 14px 24px 0; }
        .alert {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 16px; border-radius: 11px; font-size: 13px; font-weight: 500;
        }
        .alert svg { width: 15px; height: 15px; flex-shrink: 0; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }

        .card {
            background: #ffffff; border-radius: 16px;
            border: 1px solid #f5deb3; padding: 24px;
            box-shadow: 0 1px 5px rgba(92,61,30,.06);
        }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; font-weight: 600; line-height: 1;
            padding: 9px 18px; border-radius: 10px; border: none;
            cursor: pointer; text-decoration: none; white-space: nowrap;
            transition: background .15s, box-shadow .15s, transform .12s, border-color .15s, color .15s;
            vertical-align: middle;
        }
        .btn svg { width: 14px; height: 14px; flex-shrink: 0; }
        .btn:disabled { opacity: .5; cursor: not-allowed; pointer-events: none; }
        .btn-primary { background: #5c3d1e; color: #fff; box-shadow: 0 2px 6px rgba(92,61,30,.25); }
        .btn-primary:hover { background: #472e16; box-shadow: 0 3px 10px rgba(92,61,30,.35); transform: translateY(-1px); color: #fff; }
        .btn-secondary { background: #fff; color: #5c3d1e; border: 1.5px solid #5c3d1e; }
        .btn-secondary:hover { background: #5c3d1e; color: #fff; }
        .btn-danger { background: #ef4444; color: #fff; box-shadow: 0 2px 5px rgba(239,68,68,.2); }
        .btn-danger:hover { background: #dc2626; transform: translateY(-1px); color: #fff; }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-warning:hover { background: #d97706; color: #fff; }
        .btn-success { background: #22c55e; color: #fff; }
        .btn-success:hover { background: #16a34a; color: #fff; }
        .btn-ghost { background: #faf0dc; color: #5c3d1e; border: 1.5px solid #f5deb3; }
        .btn-ghost:hover { background: #f5deb3; }
        .btn-info { background: #3b82f6; color: #fff; }
        .btn-info:hover { background: #2563eb; color: #fff; }
        .btn-xs  { padding: 4px 10px; font-size: 11px; border-radius: 7px; }
        .btn-sm  { padding: 7px 13px; font-size: 12px; border-radius: 8px; }
        .btn-lg  { padding: 11px 24px; font-size: 14px; border-radius: 12px; }
        .btn-xl  { padding: 13px 28px; font-size: 15px; border-radius: 14px; }
        .btn-block { width: 100%; }

        .form-label { display: block; font-size: 13px; font-weight: 600; color: #2e1d0e; margin-bottom: 5px; }
        .form-hint  { font-size: 11px; color: #a06c3e; margin-top: 4px; }
        .form-error { font-size: 11px; color: #ef4444; margin-top: 4px; font-weight: 500; }
        .form-input {
            display: block; width: 100%;
            padding: 9px 13px; border-radius: 10px;
            border: 1.5px solid #f5deb3; background: #fdf8f0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; color: #2e1d0e;
            outline: none; transition: border-color .15s, box-shadow .15s, background .15s;
            appearance: none; -webkit-appearance: none;
        }
        .form-input:focus { border-color: #5c3d1e; background: #fff; box-shadow: 0 0 0 3px rgba(92,61,30,.11); }
        .form-input::placeholder { color: #d4b08a; }
        .form-input:read-only, .form-input[readonly] { background: #faf0dc; color: #a06c3e; cursor: default; }
        textarea.form-input { resize: vertical; min-height: 88px; line-height: 1.6; }
        select.form-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23a06c3e' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 11px center; background-size: 15px;
            padding-right: 34px; cursor: pointer;
        }
        input[type="file"].form-input { padding: 7px 13px; cursor: pointer; }
        input[type="file"].form-input::file-selector-button {
            padding: 4px 12px; margin-right: 10px;
            background: #5c3d1e; color: #fff; border: none; border-radius: 7px;
            font-size: 12px; font-weight: 600; font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer; transition: background .15s;
        }
        input[type="file"].form-input::file-selector-button:hover { background: #472e16; }
        .form-check { display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none; min-height: 20px; }
        .form-check-input {
            appearance: none; -webkit-appearance: none;
            width: 17px; height: 17px; flex-shrink: 0;
            border: 2px solid #d4b08a; background: #fdf8f0; border-radius: 5px; cursor: pointer;
            transition: background .15s, border-color .15s, box-shadow .15s; position: relative; margin: 0;
        }
        .form-check-input[type="radio"] { border-radius: 50%; }
        .form-check-input:hover { border-color: #5c3d1e; }
        .form-check-input:focus { outline: none; box-shadow: 0 0 0 3px rgba(92,61,30,.14); }
        .form-check-input:checked { background: #5c3d1e; border-color: #5c3d1e; }
        .form-check-input[type="checkbox"]:checked::after { content: ''; position: absolute; left: 3px; top: 0px; width: 7px; height: 11px; border: 2.5px solid #fff; border-top: none; border-left: none; transform: rotate(45deg); }
        .form-check-input[type="radio"]:checked::after { content: ''; position: absolute; left: 3px; top: 3px; width: 7px; height: 7px; border-radius: 50%; background: #fff; }
        .form-check-label { font-size: 13px; font-weight: 500; color: #2e1d0e; cursor: pointer; line-height: 1.4; }
        .toggle-wrap { display: flex; align-items: center; gap: 10px; }
        .toggle-switch { position: relative; display: inline-block; width: 42px; height: 22px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-track { position: absolute; cursor: pointer; inset: 0; background: #e8c88a; border-radius: 22px; transition: background .2s; border: 1.5px solid #d4b08a; }
        .toggle-track::before { content: ''; position: absolute; height: 14px; width: 14px; left: 2px; bottom: 2px; background: #fff; border-radius: 50%; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
        .toggle-switch input:checked + .toggle-track { background: #5c3d1e; border-color: #5c3d1e; }
        .toggle-switch input:checked + .toggle-track::before { transform: translateX(20px); }
        .toggle-switch input:focus + .toggle-track { box-shadow: 0 0 0 3px rgba(92,61,30,.14); }
        .toggle-label { font-size: 13px; font-weight: 500; color: #2e1d0e; cursor: pointer; }
        .input-group { display: flex; }
        .input-group .form-input { border-radius: 0; flex: 1; }
        .input-group .form-input:first-child { border-radius: 10px 0 0 10px; }
        .input-group .form-input:last-child  { border-radius: 0 10px 10px 0; }
        .input-addon { padding: 9px 13px; background: #faf0dc; border: 1.5px solid #f5deb3; font-size: 13px; color: #5c3d1e; font-weight: 600; display: flex; align-items: center; white-space: nowrap; }
        .input-addon:first-child { border-right: none; border-radius: 10px 0 0 10px; }
        .input-addon:last-child  { border-left:  none; border-radius: 0 10px 10px 0; }

        .badge { display: inline-flex; align-items: center; gap: 3px; padding: 3px 9px; border-radius: 999px; font-size: 11px; font-weight: 600; line-height: 1.4; white-space: nowrap; }
        .badge-green  { background: #dcfce7; color: #166534; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef9c3; color: #854d0e; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-gray   { background: #f3f4f6; color: #374151; }
        .badge-coffee { background: #f5deb3; color: #5c3d1e; }
        .badge-orange { background: #ffedd5; color: #c2410c; }

        .table-wrap { overflow-x: auto; border-radius: 0 0 16px 16px; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .data-table thead tr { background: #faf0dc; }
        .data-table thead th { padding: 10px 16px; text-align: left; font-size: 10.5px; font-weight: 700; color: #a06c3e; text-transform: uppercase; letter-spacing: .05em; white-space: nowrap; }
        .data-table tbody tr { border-top: 1px solid #fdf8f0; transition: background .1s; }
        .data-table tbody tr:hover { background: #fdf8f0; }
        .data-table tbody td { padding: 11px 16px; color: #2e1d0e; vertical-align: middle; }
        .data-table tbody td.muted { color: #a06c3e; font-size: 12px; }

        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.42); backdrop-filter: blur(3px); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 16px; }
        .modal-overlay.hidden { display: none; }
        .modal-box { background: #fff; border-radius: 18px; box-shadow: 0 24px 64px rgba(0,0,0,.18); width: 100%; max-width: 480px; overflow: hidden; }
        .modal-header { padding: 18px 22px 14px; border-bottom: 1px solid #f5deb3; display: flex; align-items: center; justify-content: space-between; }
        .modal-title { font-size: 15px; font-weight: 700; color: #2e1d0e; }
        .modal-body  { padding: 18px 22px; }
        .modal-footer { padding: 14px 22px; border-top: 1px solid #f5deb3; display: flex; gap: 8px; justify-content: flex-end; }
        .btn-modal-close { background: none; border: none; cursor: pointer; color: #a06c3e; padding: 4px; border-radius: 6px; transition: all .15s; }
        .btn-modal-close:hover { background: #fee2e2; color: #ef4444; }
        .btn-modal-close svg { width: 16px; height: 16px; }

        .divider { height: 1px; background: #f5deb3; margin: 16px 0; }
        .empty-state { text-align: center; padding: 48px 24px; color: #d4b08a; }
        .empty-icon  { font-size: 38px; margin-bottom: 10px; display: block; opacity: .6; }
        .stat-card { background: #fff; border-radius: 14px; padding: 18px 20px; border: 1px solid #f5deb3; box-shadow: 0 1px 5px rgba(92,61,30,.06); transition: box-shadow .2s, transform .2s; }
        .stat-card:hover { box-shadow: 0 4px 14px rgba(92,61,30,.1); transform: translateY(-2px); }
    </style>
    @yield('styles')
</head>
<body>
@php $isAdmin = auth()->user()->role === 'admin'; @endphp
<div class="app-wrap">

    {{-- ══════════ SIDEBAR ══════════ --}}
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="sidebar-logo-text">
                <strong>MyPOS</strong>
                <small>Cafe Management</small>
            </div>
        </a>

        <nav class="sidebar-nav">
            <span class="sidebar-section">Utama</span>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('pos.index') }}" class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                POS Kasir
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="sidebar-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Data Transaksi
            </a>

            <span class="sidebar-section">Menu & Stok</span>
            <a href="{{ route('admin.menus.index') }}" class="sidebar-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Data Menu
                @if(!$isAdmin)<span class="badge-ro">View</span>@endif
            </a>
            @if($isAdmin)
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                Kategori
            </a>
            @endif
            <a href="{{ route('admin.ingredients.index') }}" class="sidebar-link {{ request()->routeIs('admin.ingredients.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Data Bahan
                @if(!$isAdmin)<span class="badge-ro">View</span>@endif
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Inventory
                @if(!$isAdmin)<span class="badge-ro">View</span>@endif
            </a>
            @if($isAdmin)
            <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Supplier
            </a>
            @endif

            <span class="sidebar-section">Lainnya</span>
            @if($isAdmin)
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Users
            </a>
            @endif
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan
            </a>
            @if($isAdmin)
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pengaturan
            </a>
            @endif
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-avatar {{ !$isAdmin ? 'kasir' : '' }}">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div style="flex:1;min-width:0;">
                <span class="sidebar-user-name">{{ auth()->user()->name }}</span>
                <span class="sidebar-user-role">{{ $isAdmin ? '👑 Admin' : '🧑‍💼 Kasir' }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="margin:0">
                @csrf
                <button type="button" class="btn-logout" title="Logout" onclick="confirmLogout()">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════ MAIN ══════════ --}}
    <div class="main-wrap">
        <header class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title','Dashboard')</div>
                <div class="topbar-sub">@yield('page-subtitle','')</div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('landing') }}" target="_blank" class="topbar-pill">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Landing
                </a>
                <span class="topbar-sep">|</span>
                <span class="topbar-time" id="topbar-clock">{{ now()->format('d M Y') }}</span>
            </div>
        </header>

        {{-- Banner kasir mode --}}
        @if(!$isAdmin)
        <div class="kasir-banner">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Mode <strong>Kasir</strong> — Beberapa fitur dibatasi. Hubungi Admin untuk akses penuh.
        </div>
        @endif

        @if(session('success'))
        <div class="alerts-wrap">
            <div class="alert alert-success" id="alert-success">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="alerts-wrap">
            <div class="alert alert-error">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
        </div>
        @endif

        <main class="main-content">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function tick(){
        const el = document.getElementById('topbar-clock');
        if(el) el.textContent = new Date().toLocaleString('id-ID',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'});
        setTimeout(tick, 30000);
    })();

    setTimeout(()=>{
        const el = document.getElementById('alert-success');
        if(el){ el.style.transition='opacity .5s'; el.style.opacity='0'; setTimeout(()=>el.remove(),500); }
    }, 4000);

    function confirmLogout() {
        Swal.fire({
            title: 'Keluar dari MyPOS?',
            text: 'Sesi Anda akan diakhiri.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#5c3d1e',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then(r => { if(r.isConfirmed) document.getElementById('logout-form').submit(); });
    }

    document.addEventListener('DOMContentLoaded', ()=>{
        document.querySelectorAll('[data-confirm]').forEach(btn => {
            btn.addEventListener('click', function(e){
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: this.dataset.confirmTitle || 'Konfirmasi',
                    text: this.dataset.confirm || 'Yakin ingin melanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: this.dataset.confirmOk || 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(r => { if(r.isConfirmed && form) form.submit(); });
            });
        });
    });
</script>
@yield('scripts')
</body>
</html>
