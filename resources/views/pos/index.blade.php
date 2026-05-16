<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Kasir — MyPOS</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; overflow: hidden; touch-action: manipulation; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fdf8f0; color: #2e1d0e; }
        button, a { cursor: pointer; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-thumb { background: #d4b08a; border-radius: 3px; }

        .pos-shell { display: flex; flex-direction: column; height: 100vh; }

        /* Top Bar */
        .pos-topbar {
            background: #fff; border-bottom: 1px solid #f5deb3;
            padding: 0 16px; height: 52px;
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0; box-shadow: 0 1px 4px rgba(92,61,30,.06); z-index: 10;
        }
        .topbar-left  { display: flex; align-items: center; gap: 10px; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .pos-logo { width: 34px; height: 34px; background: #5c3d1e; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
        .pos-logo svg { width: 18px; height: 18px; }
        .pos-title { font-size: 14px; font-weight: 700; color: #2e1d0e; line-height: 1.2; }
        .pos-clock  { font-size: 11px; color: #a06c3e; }
        .topbar-btn { background: #faf0dc; border: 1.5px solid #f5deb3; color: #5c3d1e; font-size: 12px; font-weight: 600; padding: 6px 12px; border-radius: 8px; font-family: inherit; transition: background .15s; }
        .topbar-btn:hover { background: #f5deb3; }
        .trx-pill { display: none; align-items: center; gap: 6px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 999px; }
        .trx-pill.show { display: flex; }
        .pulse { animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.35} }

        .pos-body { flex: 1; display: flex; overflow: hidden; min-height: 0; }

        /* CART */
        .cart-panel { width: 278px; min-width: 278px; background: #fff; border-right: 1px solid #f5deb3; display: flex; flex-direction: column; overflow: hidden; }
        .order-tabs { padding: 10px 10px 0; display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
        .order-tab { padding: 8px 6px; border-radius: 9px; font-size: 12px; font-weight: 600; border: 1.5px solid transparent; font-family: inherit; transition: all .15s; }
        .order-tab.active { background: #5c3d1e; color: #fff; border-color: #5c3d1e; }
        .order-tab:not(.active) { background: #faf0dc; color: #5c3d1e; border-color: #f5deb3; }
        .table-indicator { display: none; margin: 6px 10px 0; background: #fdf8f0; border: 1px solid #f5deb3; border-radius: 8px; padding: 6px 10px; font-size: 11px; color: #5c3d1e; font-weight: 600; text-align: center; }
        .table-indicator.show { display: block; }
        .cart-items { flex: 1; overflow-y: auto; padding: 8px; }
        .cart-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #d4b08a; padding: 20px; text-align: center; }
        .cart-empty-icon { font-size: 36px; margin-bottom: 8px; opacity: .5; }
        .cart-item { background: #fdf8f0; border: 1px solid #f5deb3; border-radius: 10px; padding: 9px 10px; margin-bottom: 6px; animation: slideIn .2s ease; }
        @keyframes slideIn { from{opacity:0;transform:translateX(8px)} to{opacity:1;transform:translateX(0)} }
        .cart-item-top { display: flex; align-items: flex-start; gap: 6px; margin-bottom: 7px; }
        .cart-item-name { flex: 1; font-size: 12px; font-weight: 600; color: #2e1d0e; line-height: 1.3; }
        .cart-item-unit { font-size: 10.5px; color: #a06c3e; margin-top: 1px; }
        .cart-item-del  { background: none; border: none; color: #d4b08a; padding: 2px; border-radius: 5px; transition: color .15s; flex-shrink: 0; }
        .cart-item-del:hover { color: #ef4444; }
        .cart-item-del svg { width: 13px; height: 13px; }
        .cart-item-bottom { display: flex; align-items: center; justify-content: space-between; }
        .qty-control { display: flex; align-items: center; gap: 4px; }
        .qty-btn { width: 26px; height: 26px; border-radius: 7px; background: #f5deb3; border: none; color: #5c3d1e; font-size: 15px; font-weight: 700; font-family: inherit; display: flex; align-items: center; justify-content: center; transition: background .12s, transform .1s; }
        .qty-btn:active { transform: scale(.9); }
        .qty-btn:hover { background: #e8c88a; }
        .qty-display { min-width: 32px; height: 26px; background: #fff; border: 1.5px solid #f5deb3; border-radius: 7px; font-size: 13px; font-weight: 700; color: #2e1d0e; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: border-color .15s; }
        .qty-display:hover { border-color: #5c3d1e; }
        .cart-item-price { font-size: 13px; font-weight: 700; color: #5c3d1e; }
        .cart-totals { border-top: 1px solid #f5deb3; padding: 10px 12px; background: #fdf8f0; }
        .total-row { display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #5c3d1e; margin-bottom: 4px; }
        .total-row.grand { font-size: 15px; font-weight: 800; color: #2e1d0e; margin-top: 6px; padding-top: 6px; border-top: 1px dashed #e8c88a; }
        .discount-input { width: 90px; padding: 3px 8px; border-radius: 7px; border: 1.5px solid #f5deb3; background: #fff; font-size: 12px; font-weight: 600; color: #2e1d0e; text-align: right; outline: none; font-family: inherit; }
        .discount-input:focus { border-color: #5c3d1e; }
        .btn-checkout { display: block; width: calc(100% - 20px); padding: 13px; background: #5c3d1e; color: #fff; font-size: 14px; font-weight: 700; font-family: inherit; border: none; border-radius: 11px; margin: 10px; transition: background .15s, transform .12s; box-shadow: 0 3px 10px rgba(92,61,30,.25); }
        .btn-checkout:hover { background: #472e16; transform: translateY(-1px); }
        .btn-checkout:active { transform: scale(.98); }

        /* MENU PANEL */
        .menu-panel { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }
        .menu-header { background: #fff; border-bottom: 1px solid #f5deb3; padding: 10px 12px; flex-shrink: 0; }
        .menu-search-wrap { position: relative; margin-bottom: 8px; }
        .menu-search-icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #d4b08a; }
        .menu-search-icon svg { width: 14px; height: 14px; }
        .menu-search { width: 100%; padding: 8px 12px 8px 32px; border: 1.5px solid #f5deb3; border-radius: 9px; background: #fdf8f0; font-size: 13px; color: #2e1d0e; outline: none; font-family: inherit; transition: border-color .15s; }
        .menu-search:focus { border-color: #5c3d1e; background: #fff; }
        .menu-search::placeholder { color: #d4b08a; }
        .cat-scroll { display: flex; gap: 6px; overflow-x: auto; padding-bottom: 2px; }
        .cat-scroll::-webkit-scrollbar { height: 0; }
        .cat-btn { flex-shrink: 0; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 600; font-family: inherit; border: 1.5px solid #f5deb3; background: #fff; color: #5c3d1e; transition: all .15s; white-space: nowrap; }
        .cat-btn:hover { background: #faf0dc; }
        .cat-btn.active { background: #5c3d1e; color: #fff; border-color: #5c3d1e; }
        .menu-grid-wrap { flex: 1; overflow-y: auto; padding: 10px; }
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 8px; }

        /* ── Menu Card ── */
        .menu-card { background: #fff; border: 1.5px solid #f5deb3; border-radius: 12px; overflow: hidden; cursor: pointer; transition: transform .15s, box-shadow .15s, border-color .15s; user-select: none; -webkit-tap-highlight-color: transparent; }
        .menu-card:hover  { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(92,61,30,.12); border-color: #e8c88a; }
        .menu-card:active { transform: scale(.96); }

        /* Image wrapper — fixed ratio */
        .menu-card-img {
            position: relative;
            width: 100%;
            padding-top: 72px; /* fixed height */
            background: linear-gradient(135deg, #faf0dc, #f5deb3);
            overflow: hidden;
        }
        .menu-card-img img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s ease;
        }
        .menu-card:hover .menu-card-img img { transform: scale(1.07); }
        .menu-card-img .emoji-fallback {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        /* Skeleton shimmer */
        .img-skeleton {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, #f5deb3 25%, #fdf8f0 50%, #f5deb3 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
        }
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        .menu-card-body { padding: 8px; }
        .menu-card-name { font-size: 11.5px; font-weight: 600; color: #2e1d0e; line-height: 1.3; margin-bottom: 3px; }
        .menu-card-price { font-size: 12px; font-weight: 700; color: #5c3d1e; }

        /* NUMPAD */
        .numpad-panel { width: 300px; min-width: 300px; background: #fff; border-left: 1px solid #f5deb3; display: flex; flex-direction: column; padding: 10px; gap: 8px; overflow: hidden; }
        .numpad-context { background: #fdf8f0; border: 1.5px solid #f5deb3; border-radius: 10px; padding: 8px 12px; }
        .numpad-ctx-label { font-size: 10px; font-weight: 600; color: #a06c3e; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 2px; }
        .numpad-ctx-item  { font-size: 12px; font-weight: 600; color: #2e1d0e; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .numpad-display { background: #2e1d0e; border-radius: 10px; padding: 12px 16px; text-align: right; font-size: 28px; font-weight: 800; color: #fff; min-height: 58px; display: flex; align-items: center; justify-content: flex-end; letter-spacing: -1px; overflow: hidden; }
        .numpad-display .prefix { font-size: 14px; font-weight: 400; color: #a06c3e; margin-right: 4px; }
        .numpad-mode-tabs { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
        .nmtab { padding: 7px; border-radius: 8px; font-size: 12px; font-weight: 600; border: 1.5px solid #f5deb3; background: #faf0dc; color: #5c3d1e; font-family: inherit; transition: all .15s; }
        .nmtab.active { background: #5c3d1e; color: #fff; border-color: #5c3d1e; }
        .numpad-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 7px; flex: 1; }
        .np { border: none; border-radius: 11px; font-family: inherit; font-weight: 700; display: flex; align-items: center; justify-content: center; transition: transform .1s, background .12s; cursor: pointer; user-select: none; -webkit-tap-highlight-color: transparent; min-height: 52px; }
        .np:active { transform: scale(.91); }
        .np-digit { background: #faf0dc; color: #2e1d0e; font-size: 20px; }
        .np-digit:hover { background: #f5deb3; }
        .np-zero  { background: #faf0dc; color: #2e1d0e; font-size: 20px; }
        .np-zero:hover { background: #f5deb3; }
        .np-dbl   { background: #f5deb3; color: #5c3d1e; font-size: 16px; }
        .np-dbl:hover { background: #e8c88a; }
        .np-back  { background: #fff0f0; color: #ef4444; font-size: 16px; }
        .np-back:hover { background: #fee2e2; }
        .np-clear { background: #fef9c3; color: #854d0e; font-size: 13px; font-weight: 700; }
        .np-clear:hover { background: #fef08a; }
        .np-enter { background: #5c3d1e; color: #fff; font-size: 13px; font-weight: 700; border-radius: 11px; }
        .np-enter:hover { background: #472e16; }
        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
        .act-btn { padding: 11px 6px; border-radius: 10px; border: none; font-size: 12px; font-weight: 700; font-family: inherit; display: flex; flex-direction: column; align-items: center; gap: 3px; transition: transform .12s, background .15s; }
        .act-btn:active { transform: scale(.95); }
        .act-btn .icon { font-size: 18px; }
        .act-dine   { background: #dbeafe; color: #1e40af; }
        .act-take   { background: #fef9c3; color: #854d0e; }
        .act-table  { background: #f0fdf4; color: #166534; }
        .act-hold   { background: #fff7ed; color: #9a3412; }
        .act-cancel { background: #fef2f2; color: #991b1b; }
        .act-checkout { background: #5c3d1e; color: #fff; grid-column: span 2; font-size: 14px; padding: 13px; }
        .act-btn:hover { filter: brightness(.95); }

        /* MODAL */
        .modal-overlay { position: fixed; inset: 0; z-index: 999; background: rgba(0,0,0,.45); backdrop-filter: blur(3px); display: flex; align-items: center; justify-content: center; padding: 16px; }
        .modal-overlay.hidden { display: none; }
        .modal-box { background: #fff; border-radius: 18px; box-shadow: 0 24px 64px rgba(0,0,0,.2); width: 100%; max-width: 860px; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
        .modal-head { padding: 16px 20px; border-bottom: 1px solid #f5deb3; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .modal-title { font-size: 15px; font-weight: 700; color: #2e1d0e; }
        .modal-close { background: none; border: none; color: #a06c3e; padding: 4px; border-radius: 7px; cursor: pointer; transition: all .15s; }
        .modal-close:hover { background: #fee2e2; color: #ef4444; }
        .modal-close svg { width: 18px; height: 18px; }
        .cafe-floor { flex: 1; overflow: auto; position: relative; background: repeating-linear-gradient(0deg,transparent,transparent 39px,#f5deb3 39px,#f5deb3 40px), repeating-linear-gradient(90deg,transparent,transparent 39px,#f5deb3 39px,#f5deb3 40px); background-color: #fdf8f0; min-height: 400px; }
        .floor-label { position: absolute; top: 10px; left: 12px; font-size: 11px; color: #d4b08a; font-weight: 600; }
        .floor-legend { position: absolute; bottom: 10px; right: 12px; display: flex; gap: 12px; }
        .legend-item { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #a06c3e; font-weight: 500; }
        .legend-dot { width: 10px; height: 10px; border-radius: 3px; }
        .table-node { position: absolute; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; border-radius: 12px; border: 2px solid; cursor: pointer; transition: transform .15s, box-shadow .15s; font-weight: 700; user-select: none; }
        .table-node:hover  { transform: scale(1.06); }
        .table-node:active { transform: scale(.96); }
        .table-node.avail    { background: #f0fdf4; border-color: #86efac; color: #166534; }
        .table-node.occupied { background: #fff7ed; border-color: #fdba74; color: #9a3412; }
        .co-box { max-width: 420px; }
        .pm-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-bottom: 12px; }
        .pm-btn { padding: 12px 6px; border-radius: 11px; border: 2px solid #f5deb3; background: #fdf8f0; font-family: inherit; font-size: 12px; font-weight: 600; color: #5c3d1e; display: flex; flex-direction: column; align-items: center; gap: 4px; transition: all .15s; cursor: pointer; }
        .pm-btn .icon { font-size: 22px; }
        .pm-btn.active { border-color: #5c3d1e; background: #f5deb3; }
        .pm-btn:hover  { border-color: #d4b08a; }
        .paid-input-wrap { position: relative; }
        .paid-prefix { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 13px; font-weight: 600; color: #a06c3e; }
        .paid-input { width: 100%; padding: 12px 14px 12px 36px; border: 2px solid #f5deb3; border-radius: 11px; background: #fdf8f0; font-size: 18px; font-weight: 700; color: #2e1d0e; outline: none; font-family: inherit; transition: border-color .15s; }
        .paid-input:focus { border-color: #5c3d1e; background: #fff; }
        .quick-amounts { display: grid; grid-template-columns: repeat(5,1fr); gap: 5px; margin-top: 8px; }
        .qa-btn { padding: 7px 4px; border-radius: 8px; background: #faf0dc; border: 1px solid #f5deb3; font-size: 11px; font-weight: 600; color: #5c3d1e; font-family: inherit; transition: background .12s; }
        .qa-btn:hover { background: #f5deb3; }
        .change-display { background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 10px; padding: 11px 14px; display: flex; justify-content: space-between; align-items: center; margin-top: 8px; }
        .change-label  { font-size: 13px; font-weight: 600; color: #166534; }
        .change-amount { font-size: 18px; font-weight: 800; color: #166534; }
        .btn-pay { width: 100%; margin-top: 12px; padding: 14px; background: #5c3d1e; color: #fff; border: none; border-radius: 12px; font-size: 15px; font-weight: 700; font-family: inherit; transition: background .15s; box-shadow: 0 3px 12px rgba(92,61,30,.25); }
        .btn-pay:hover { background: #472e16; }
        .receipt-box { max-width: 360px; }
        .receipt-body { padding: 16px 20px; font-size: 13px; }
        .receipt-btns { padding: 14px 20px; border-top: 1px solid #f5deb3; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .receipt-btn { padding: 11px; border-radius: 10px; border: none; font-size: 13px; font-weight: 600; font-family: inherit; transition: background .15s; }
        .receipt-btn-print { background: #5c3d1e; color: #fff; }
        .receipt-btn-print:hover { background: #472e16; }
        .receipt-btn-new   { background: #22c55e; color: #fff; }
        .receipt-btn-new:hover { background: #16a34a; }
    </style>
</head>
<body>
<div class="pos-shell">

    {{-- TOP BAR --}}
    <header class="pos-topbar">
        <div class="topbar-left">
            <a href="{{ route('admin.dashboard') }}" style="color:#d4b08a;line-height:0;transition:color .15s;" onmouseover="this.style.color='#5c3d1e'" onmouseout="this.style.color='#d4b08a'" title="Kembali ke Dashboard">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="pos-logo">
                <svg fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="pos-title">MyPOS Kasir</div>
                <div class="pos-clock" id="pos-clock">--:--:--</div>
            </div>
        </div>
        <div class="topbar-right">
            <span style="font-size:12px;color:#a06c3e;font-weight:500;">{{ auth()->user()->name }}</span>
            <div class="trx-pill" id="trx-pill">
                <span style="width:6px;height:6px;background:#22c55e;border-radius:50%;" class="pulse"></span>
                <span id="trx-invoice-label">—</span>
            </div>
        </div>
    </header>

    {{-- BODY --}}
    <div class="pos-body">

        {{-- CART (left) --}}
        <div class="cart-panel">
            <div class="order-tabs">
                <button class="order-tab active" id="tab-dine" onclick="setOrderType('dine_in')">🪑 Dine In</button>
                <button class="order-tab" id="tab-take" onclick="setOrderType('takeaway')">🥡 Takeaway</button>
            </div>
            <div class="table-indicator" id="table-indicator">📍 <span id="table-name-label">—</span></div>

            <div class="cart-items" id="cart-items">
                <div class="cart-empty" id="cart-empty">
                    <div class="cart-empty-icon">🛒</div>
                    <div style="font-size:13px;font-weight:600;color:#a06c3e;">Keranjang kosong</div>
                    <div style="font-size:11px;color:#d4b08a;margin-top:3px;">Tap menu di kanan untuk menambah</div>
                </div>
            </div>

            <div class="cart-totals">
                <div class="total-row"><span>Subtotal</span><span id="disp-subtotal">Rp 0</span></div>
                <div class="total-row"><span>Pajak</span><span id="disp-tax">Rp 0</span></div>
                <div class="total-row">
                    <span>Diskon</span>
                    <input type="number" id="discount-input" value="0" min="0" class="discount-input" oninput="recalcDisplay()">
                </div>
                <div class="total-row grand"><span>Total</span><span id="disp-total">Rp 0</span></div>
            </div>

            <button class="btn-checkout" onclick="openCheckout()">💳 Checkout</button>
        </div>

        {{-- MENU PANEL (center) --}}
        <div class="menu-panel">
            <div class="menu-header">
                <div class="menu-search-wrap">
                    <div class="menu-search-icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="menu-search" class="menu-search" placeholder="Cari menu...">
                </div>
                <div class="cat-scroll" id="cat-scroll">
                    <button class="cat-btn active" data-cat="all" onclick="filterCat('all')">☕ Semua</button>
                    @foreach($categories as $cat)
                        @if($cat->slug !== 'all')
                        <button class="cat-btn" data-cat="{{ $cat->slug }}" onclick="filterCat('{{ $cat->slug }}')">{{ $cat->icon }} {{ $cat->name }}</button>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="menu-grid-wrap">
                <div class="menu-grid" id="menu-grid">
                    <div style="grid-column:1/-1;text-align:center;padding:40px;color:#d4b08a;">
                        <div style="font-size:28px;margin-bottom:8px;">☕</div>
                        <div style="font-size:13px;">Memuat menu...</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- NUMPAD (right) --}}
        <div class="numpad-panel">
            <div class="numpad-context">
                <div class="numpad-ctx-label">Numpad aktif untuk</div>
                <div class="numpad-ctx-item" id="np-ctx-item">— Pilih item di cart —</div>
            </div>
            <div class="numpad-mode-tabs">
                <button class="nmtab active" id="nm-qty"  onclick="setNpMode('qty')">🔢 Qty</button>
                <button class="nmtab"        id="nm-disc" onclick="setNpMode('discount')">🏷️ Diskon</button>
            </div>
            <div class="numpad-display" id="np-display">
                <span class="prefix" id="np-prefix">×</span>
                <span id="np-value">0</span>
            </div>
            <div class="numpad-grid">
                <button class="np np-digit" onclick="npInput('7')">7</button>
                <button class="np np-digit" onclick="npInput('8')">8</button>
                <button class="np np-digit" onclick="npInput('9')">9</button>
                <button class="np np-digit" onclick="npInput('4')">4</button>
                <button class="np np-digit" onclick="npInput('5')">5</button>
                <button class="np np-digit" onclick="npInput('6')">6</button>
                <button class="np np-digit" onclick="npInput('1')">1</button>
                <button class="np np-digit" onclick="npInput('2')">2</button>
                <button class="np np-digit" onclick="npInput('3')">3</button>
                <button class="np np-dbl"   onclick="npInput('000')">000</button>
                <button class="np np-zero"  onclick="npInput('0')">0</button>
                <button class="np np-back"  onclick="npBack()">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z"/></svg>
                </button>
                <button class="np np-clear" onclick="npClear()" style="font-size:14px;">CLEAR</button>
                <button class="np np-enter" onclick="npConfirm()" style="grid-column:span 2;">✓ OK</button>
            </div>
            <div class="action-grid">
                <button class="act-btn act-dine"   id="act-dine"  onclick="setOrderType('dine_in')"><span class="icon">🪑</span>Dine In</button>
                <button class="act-btn act-take"   id="act-take"  onclick="setOrderType('takeaway')"><span class="icon">🥡</span>Takeaway</button>
                <button class="act-btn act-table"  onclick="openTableModal()"><span class="icon">🗺️</span>Meja</button>
                <button class="act-btn act-hold"   onclick="holdOrder()"><span class="icon">⏸</span>Hold</button>
                <button class="act-btn act-cancel" onclick="cancelOrder()"><span class="icon">✕</span>Batal</button>
                <button class="act-btn act-checkout" onclick="openCheckout()">💳 Checkout</button>
            </div>
        </div>
    </div>
</div>

{{-- TABLE MODAL --}}
<div id="table-modal" class="modal-overlay hidden">
    <div class="modal-box">
        <div class="modal-head">
            <div>
                <div class="modal-title">🪑 Denah Meja Cafe</div>
                <div style="font-size:11px;color:#a06c3e;margin-top:2px;">Tap meja untuk memulai / melanjutkan pesanan</div>
            </div>
            <button class="modal-close" onclick="closeTableModal()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="cafe-floor" id="cafe-floor" style="min-height:440px;">
            <div class="floor-label">📍 Area Cafe</div>
            <div class="floor-legend">
                <div class="legend-item"><div class="legend-dot" style="background:#86efac;"></div>Kosong</div>
                <div class="legend-item"><div class="legend-dot" style="background:#fdba74;"></div>Terisi</div>
            </div>
        </div>
    </div>
</div>

{{-- CHECKOUT MODAL --}}
<div id="checkout-modal" class="modal-overlay hidden">
    <div class="modal-box co-box">
        <div class="modal-head">
            <div class="modal-title">💳 Proses Pembayaran</div>
            <button class="modal-close" onclick="closeCheckout()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div style="padding:16px 20px;">
            <div style="background:#fdf8f0;border:1px solid #f5deb3;border-radius:11px;padding:12px 14px;margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;font-size:12px;color:#5c3d1e;margin-bottom:3px;"><span>Subtotal</span><span id="co-subtotal">Rp 0</span></div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:#a06c3e;margin-bottom:3px;"><span>Pajak</span><span id="co-tax">Rp 0</span></div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:#a06c3e;margin-bottom:3px;"><span>Diskon</span><span id="co-disc">Rp 0</span></div>
                <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:800;color:#2e1d0e;margin-top:8px;padding-top:8px;border-top:1px dashed #e8c88a;"><span>Total Bayar</span><span id="co-total">Rp 0</span></div>
            </div>
            <div style="font-size:11px;font-weight:700;color:#a06c3e;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">Metode Pembayaran</div>
            <div class="pm-grid">
                <button class="pm-btn" data-pm="cash"     onclick="selectPM('cash')">    <span class="icon">💵</span>Cash</button>
                <button class="pm-btn" data-pm="transfer" onclick="selectPM('transfer')"><span class="icon">🏦</span>Transfer</button>
                <button class="pm-btn" data-pm="qris"     onclick="selectPM('qris')">    <span class="icon">📱</span>QRIS</button>
            </div>
            <div style="font-size:11px;font-weight:700;color:#a06c3e;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">Jumlah Bayar</div>
            <div class="paid-input-wrap">
                <span class="paid-prefix">Rp</span>
                <input type="number" id="paid-amount" class="paid-input" placeholder="0" oninput="updateChange()">
            </div>
            <div class="quick-amounts">
                <button class="qa-btn" onclick="setExact()">Pas</button>
                <button class="qa-btn" onclick="addPaid(10000)">+10K</button>
                <button class="qa-btn" onclick="addPaid(20000)">+20K</button>
                <button class="qa-btn" onclick="addPaid(50000)">+50K</button>
                <button class="qa-btn" onclick="addPaid(100000)">+100K</button>
            </div>
            <div class="change-display">
                <span class="change-label">Kembalian</span>
                <span class="change-amount" id="change-display">Rp 0</span>
            </div>
            <input type="text" id="co-notes" placeholder="Catatan (opsional)..."
                style="width:100%;margin-top:10px;padding:9px 13px;border:1.5px solid #f5deb3;border-radius:10px;font-size:13px;font-family:inherit;color:#2e1d0e;background:#fdf8f0;outline:none;">
        </div>
        <div style="padding:0 20px 16px;">
            <button class="btn-pay" id="btn-pay" onclick="processPayment()">✅ Proses Pembayaran</button>
        </div>
    </div>
</div>

{{-- RECEIPT MODAL --}}
<div id="receipt-modal" class="modal-overlay hidden">
    <div class="modal-box receipt-box">
        <div class="modal-head">
            <div class="modal-title">🧾 Struk Pembayaran</div>
            <button class="modal-close" onclick="closeReceipt()">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="receipt-body" id="receipt-content"></div>
        <div class="receipt-btns">
            <button class="receipt-btn receipt-btn-print" onclick="printReceipt()">🖨️ Print / PDF</button>
            <button class="receipt-btn receipt-btn-new"   onclick="newTransaction()">➕ Baru</button>
        </div>
    </div>
</div>

<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ── Resolve image src: URL langsung atau storage lokal
function resolveImg(image) {
    if (!image) return null;
    // Jika sudah berupa URL penuh (Unsplash, dll) — pakai langsung
    if (image.startsWith('http://') || image.startsWith('https://')) return image;
    // Jika path lokal — pakai /storage/
    return '/storage/' + image;
}

// ── Build menu card HTML dengan smart image handling
function menuCardHtml(m) {
    const src  = resolveImg(m.image);
    const icon = m.category?.icon || '☕';
    const imgHtml = src
        ? `<div class="img-skeleton" id="sk-${m.id}"></div>
           <img
             src="${src}"
             alt="${escHtml(m.name)}"
             loading="lazy"
             onload="this.previousElementSibling.style.display='none'"
             onerror="this.style.display='none';this.previousElementSibling.style.display='none';document.getElementById('ef-${m.id}').style.display='flex'"
           >
           <div class="emoji-fallback" id="ef-${m.id}" style="display:none">${icon}</div>`
        : `<div class="emoji-fallback">${icon}</div>`;

    return `
      <div class="menu-card" onclick="addToCart(${m.id})">
        <div class="menu-card-img" style="padding-top:72px;">${imgHtml}</div>
        <div class="menu-card-body">
          <div class="menu-card-name">${escHtml(m.name)}</div>
          <div class="menu-card-price">Rp ${fmt(m.price)}</div>
        </div>
      </div>`;
}

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ════════════════════════════════
//  STATE
// ════════════════════════════════
const S = {
    trx: null, orderType: 'dine_in',
    tableId: null, tableName: null, paymentMethod: null,
    npMode: 'qty', npValue: '', npItemId: null, npItemPrice: 0,
};

document.addEventListener('DOMContentLoaded', () => {
    loadMenus();
    startClock();
    document.getElementById('menu-search').addEventListener('input', () => loadMenus());
    document.getElementById('discount-input').addEventListener('input', recalcDisplay);
});

function startClock() {
    const el = document.getElementById('pos-clock');
    (function tick(){
        el.textContent = new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
        setTimeout(tick, 1000);
    })();
}

// ════════════════════════════════
//  MENUS
// ════════════════════════════════
async function loadMenus() {
    const cat    = document.querySelector('.cat-btn.active')?.dataset.cat || 'all';
    const search = document.getElementById('menu-search').value;
    const res    = await fetch(`/pos/menus?category=${cat}&search=${encodeURIComponent(search)}`,
        { headers: { 'Accept':'application/json', 'X-CSRF-TOKEN':CSRF } });
    const menus  = await res.json();
    renderMenus(menus);
}

function renderMenus(menus) {
    const grid = document.getElementById('menu-grid');
    if (!menus.length) {
        grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:40px;color:#d4b08a;">
            <div style="font-size:28px;margin-bottom:8px;">🔍</div>
            <div style="font-size:13px;">Menu tidak ditemukan</div></div>`;
        return;
    }
    grid.innerHTML = menus.map(m => menuCardHtml(m)).join('');
}

function filterCat(slug) {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.toggle('active', b.dataset.cat === slug));
    loadMenus();
}

// ════════════════════════════════
//  ORDER TYPE
// ════════════════════════════════
function setOrderType(type) {
    S.orderType = type;
    document.getElementById('tab-dine').classList.toggle('active', type==='dine_in');
    document.getElementById('tab-take').classList.toggle('active', type==='takeaway');
    document.getElementById('act-dine').classList.toggle('active', type==='dine_in');
    document.getElementById('act-take').classList.toggle('active', type==='takeaway');
    if (type === 'takeaway') { S.tableId = null; S.tableName = null; hideTableIndicator(); }
}
function showTableIndicator(name) { document.getElementById('table-name-label').textContent = name; document.getElementById('table-indicator').classList.add('show'); }
function hideTableIndicator()     { document.getElementById('table-indicator').classList.remove('show'); }

// ════════════════════════════════
//  TRANSACTION
// ════════════════════════════════
async function ensureTrx() {
    if (S.trx) return S.trx;
    const res  = await fetch('/pos/transaction/start', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({ order_type: S.orderType, table_id: S.tableId })
    });
    const data = await res.json();
    S.trx = data.transaction;
    updateTrxPill();
    return S.trx;
}

async function addToCart(menuId) {
    const trx = await ensureTrx();
    const res  = await fetch(`/pos/transaction/${trx.id}/add-item`, {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({ menu_id: menuId, quantity: 1 })
    });
    const data = await res.json();
    S.trx = data.transaction;
    renderCart();
}

async function changeQty(trxId, itemId, newQty) {
    if (newQty <= 0) { removeItem(trxId, itemId); return; }
    const res  = await fetch(`/pos/transaction/${trxId}/item/${itemId}`, {
        method: 'PUT',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({ quantity: newQty })
    });
    const data = await res.json();
    S.trx = data.transaction; renderCart();
}

async function removeItem(trxId, itemId) {
    const res  = await fetch(`/pos/transaction/${trxId}/item/${itemId}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN':CSRF,'Accept':'application/json' }
    });
    const data = await res.json();
    S.trx = data.transaction; renderCart();
    if (S.npItemId === itemId) { S.npItemId = null; setNpContext(null); }
}

// ════════════════════════════════
//  RENDER CART
// ════════════════════════════════
function renderCart() {
    const trx   = S.trx;
    const wrap  = document.getElementById('cart-items');
    const empty = document.getElementById('cart-empty');
    wrap.querySelectorAll('.cart-item').forEach(el => el.remove());
    if (!trx?.items?.length) { empty.style.display = 'flex'; recalcDisplay(); return; }
    empty.style.display = 'none';
    trx.items.forEach(item => {
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.dataset.itemId = item.id;
        div.style.borderColor = S.npItemId === item.id ? '#5c3d1e' : '';
        div.innerHTML = `
            <div class="cart-item-top">
                <div style="flex:1;min-width:0;">
                    <div class="cart-item-name">${escHtml(item.menu_name)}</div>
                    <div class="cart-item-unit">Rp ${fmt(item.price)} / pcs</div>
                </div>
                <button class="cart-item-del" onclick="removeItem(${trx.id},${item.id})">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="cart-item-bottom">
                <div class="qty-control">
                    <button class="qty-btn" onclick="changeQty(${trx.id},${item.id},${item.quantity-1})">−</button>
                    <div class="qty-display" onclick="selectCartItem(${item.id},'${escHtml(item.menu_name)}',${item.price})">${item.quantity}</div>
                    <button class="qty-btn" onclick="changeQty(${trx.id},${item.id},${item.quantity+1})">+</button>
                </div>
                <div class="cart-item-price">Rp ${fmt(item.subtotal)}</div>
            </div>`;
        wrap.insertBefore(div, empty);
    });
    recalcDisplay();
}

function recalcDisplay() {
    const sub  = S.trx?.items?.reduce((s,i) => s+parseFloat(i.subtotal), 0) || 0;
    const disc = parseFloat(document.getElementById('discount-input').value) || 0;
    const tot  = Math.max(0, sub - disc);
    document.getElementById('disp-subtotal').textContent = 'Rp ' + fmt(sub);
    document.getElementById('disp-tax').textContent      = 'Rp 0';
    document.getElementById('disp-total').textContent    = 'Rp ' + fmt(tot);
}

function updateTrxPill() {
    const pill = document.getElementById('trx-pill');
    if (S.trx) { pill.classList.add('show'); document.getElementById('trx-invoice-label').textContent = S.trx.invoice_number; }
    else pill.classList.remove('show');
}

// ════════════════════════════════
//  NUMPAD
// ════════════════════════════════
function selectCartItem(itemId, name, price) {
    S.npItemId = itemId; S.npItemPrice = price; S.npValue = '';
    updateNpDisplay(); setNpContext(name);
    document.querySelectorAll('.cart-item').forEach(el => {
        el.style.borderColor = el.dataset.itemId == itemId ? '#5c3d1e' : '';
    });
}
function setNpContext(name) { document.getElementById('np-ctx-item').textContent = name || '— Pilih item di cart —'; }
function setNpMode(mode) {
    S.npMode = mode; S.npValue = ''; updateNpDisplay();
    document.getElementById('nm-qty').classList.toggle('active',  mode === 'qty');
    document.getElementById('nm-disc').classList.toggle('active', mode === 'discount');
    document.getElementById('np-prefix').textContent = mode === 'qty' ? '×' : 'Rp';
}
function npInput(val) { if (S.npValue.length >= 9) return; S.npValue += val; S.npValue = String(parseInt(S.npValue) || 0); updateNpDisplay(); }
function npBack()  { S.npValue = S.npValue.slice(0, -1); updateNpDisplay(); }
function npClear() { S.npValue = ''; updateNpDisplay(); }
function updateNpDisplay() { const v = parseInt(S.npValue) || 0; document.getElementById('np-value').textContent = S.npMode === 'discount' ? fmt(v) : v; }
async function npConfirm() {
    const v = parseInt(S.npValue) || 0;
    if (!v) return;
    if (S.npMode === 'discount') { document.getElementById('discount-input').value = v; recalcDisplay(); S.npValue = ''; updateNpDisplay(); return; }
    if (!S.npItemId || !S.trx) { Swal.fire({ icon:'warning', title:'Pilih item dulu', text:'Tap qty item di cart untuk mengaktifkan numpad.', timer:2000, showConfirmButton:false }); return; }
    await changeQty(S.trx.id, S.npItemId, v);
    S.npValue = ''; updateNpDisplay();
}

// ════════════════════════════════
//  HOLD & CANCEL
// ════════════════════════════════
async function holdOrder() {
    if (!S.trx) { Swal.fire({ icon:'warning', title:'Tidak ada transaksi aktif', timer:1500, showConfirmButton:false }); return; }
    const r = await Swal.fire({ title:'Hold Order?', text:'Pesanan akan disimpan.', icon:'question', showCancelButton:true, confirmButtonColor:'#5c3d1e', cancelButtonColor:'#9ca3af', confirmButtonText:'Ya, Hold!', cancelButtonText:'Batal', reverseButtons:true });
    if (!r.isConfirmed) return;
    await fetch(`/pos/transaction/${S.trx.id}/hold`, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} });
    Swal.fire({ icon:'success', title:'Order di-hold', timer:2000, showConfirmButton:false });
    resetSession();
}
async function cancelOrder() {
    if (!S.trx) return;
    const r = await Swal.fire({ title:'Batalkan Transaksi?', text:'Semua item akan dihapus.', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', cancelButtonColor:'#9ca3af', confirmButtonText:'Ya, Batalkan', cancelButtonText:'Tidak', reverseButtons:true });
    if (!r.isConfirmed) return;
    await fetch(`/pos/transaction/${S.trx.id}/cancel`, { method:'POST', headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'} });
    resetSession();
    Swal.fire({ icon:'info', title:'Transaksi Dibatalkan', timer:1500, showConfirmButton:false });
}

// ════════════════════════════════
//  TABLE MODAL
// ════════════════════════════════
async function openTableModal() {
    document.getElementById('table-modal').classList.remove('hidden');
    const res    = await fetch('/pos/tables', { headers:{'Accept':'application/json'} });
    const tables = await res.json();
    renderFloor(tables);
}
function closeTableModal() { document.getElementById('table-modal').classList.add('hidden'); }
function renderFloor(tables) {
    const floor = document.getElementById('cafe-floor');
    floor.querySelectorAll('.table-node').forEach(el => el.remove());
    tables.forEach(t => {
        const w = t.capacity <= 2 ? 72 : t.capacity <= 4 ? 86 : 102;
        const h = t.capacity <= 2 ? 64 : t.capacity <= 4 ? 74 : 88;
        const node = document.createElement('div');
        node.className = 'table-node ' + (t.status === 'occupied' ? 'occupied' : 'avail');
        node.style.cssText = `left:${t.pos_x}px;top:${t.pos_y}px;width:${w}px;height:${h}px;`;
        node.innerHTML = `<span style="font-size:18px;">🪑</span><span style="font-size:11px;">${t.name}</span><span style="font-size:10px;opacity:.7;">${t.capacity} kursi</span>${t.status==='occupied'?'<span style="font-size:9px;color:#f97316;font-weight:700;">● Terisi</span>':''}`;
        node.onclick = () => selectTable(t);
        floor.appendChild(node);
    });
}
async function selectTable(t) {
    if (t.status === 'occupied' && t.active_transaction) {
        const res  = await fetch(`/pos/transaction/${t.active_transaction.id}`, { headers:{'Accept':'application/json'} });
        const data = await res.json();
        S.trx = data.transaction; updateTrxPill(); renderCart();
    }
    S.tableId = t.id; S.tableName = t.name;
    setOrderType('dine_in'); showTableIndicator(t.name); closeTableModal();
    Swal.fire({ icon:'success', title:`Meja ${t.name} dipilih`, timer:1200, showConfirmButton:false, position:'top-end', toast:true });
}

// ════════════════════════════════
//  CHECKOUT
// ════════════════════════════════
function openCheckout() {
    if (!S.trx?.items?.length) { Swal.fire({ icon:'warning', title:'Keranjang Kosong', text:'Tambahkan menu terlebih dahulu.', timer:2000, showConfirmButton:false }); return; }
    const sub  = S.trx.items.reduce((s,i) => s+parseFloat(i.subtotal), 0);
    const disc = parseFloat(document.getElementById('discount-input').value) || 0;
    const tot  = Math.max(0, sub - disc);
    document.getElementById('co-subtotal').textContent    = 'Rp ' + fmt(sub);
    document.getElementById('co-tax').textContent         = 'Rp 0';
    document.getElementById('co-disc').textContent        = 'Rp ' + fmt(disc);
    document.getElementById('co-total').textContent       = 'Rp ' + fmt(tot);
    document.getElementById('paid-amount').value          = '';
    document.getElementById('change-display').textContent = 'Rp 0';
    document.getElementById('co-notes').value             = '';
    S.paymentMethod = null;
    document.querySelectorAll('.pm-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('checkout-modal').classList.remove('hidden');
}
function closeCheckout() { document.getElementById('checkout-modal').classList.add('hidden'); }
function selectPM(pm) { S.paymentMethod = pm; document.querySelectorAll('.pm-btn').forEach(b => b.classList.toggle('active', b.dataset.pm === pm)); if (pm !== 'cash') setExact(); }
function getCoTotal() { return parseFloat(document.getElementById('co-total').textContent.replace('Rp ','').replace(/\./g,'')) || 0; }
function setExact()      { document.getElementById('paid-amount').value = getCoTotal(); updateChange(); }
function addPaid(amount) { document.getElementById('paid-amount').value = (parseFloat(document.getElementById('paid-amount').value)||0) + amount; updateChange(); }
function updateChange()  { document.getElementById('change-display').textContent = 'Rp ' + fmt(Math.max(0,(parseFloat(document.getElementById('paid-amount').value)||0) - getCoTotal())); }

async function processPayment() {
    if (!S.paymentMethod) { Swal.fire({ icon:'warning', title:'Pilih metode pembayaran', timer:1800, showConfirmButton:false }); return; }
    const paid  = parseFloat(document.getElementById('paid-amount').value) || 0;
    const total = getCoTotal();
    if (paid < total) { Swal.fire({ icon:'warning', title:'Pembayaran kurang', text:`Kurang Rp ${fmt(total-paid)}`, timer:2000, showConfirmButton:false }); return; }
    const disc  = parseFloat(document.getElementById('discount-input').value) || 0;
    const notes = document.getElementById('co-notes').value;
    const btn   = document.getElementById('btn-pay');
    btn.disabled = true; btn.textContent = 'Memproses...';
    const res  = await fetch(`/pos/transaction/${S.trx.id}/checkout`, {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json' },
        body: JSON.stringify({ payment_method: S.paymentMethod, paid_amount: paid, discount: disc, notes })
    });
    const data = await res.json();
    S.trx = data.transaction;
    closeCheckout();
    showReceipt(data.transaction);
    btn.disabled = false; btn.textContent = '✅ Proses Pembayaran';
}

// ════════════════════════════════
//  RECEIPT
// ════════════════════════════════
function showReceipt(trx) {
    const change = Math.max(0, trx.paid_amount - trx.total);
    document.getElementById('receipt-content').innerHTML = `
        <div style="text-align:center;border-bottom:1px dashed #f5deb3;padding-bottom:10px;margin-bottom:10px;">
            <div style="font-weight:700;font-size:14px;color:#2e1d0e;">☕ MyPOS Cafe</div>
            <div style="font-size:11px;color:#a06c3e;font-family:monospace;">${trx.invoice_number}</div>
            <div style="font-size:11px;color:#a06c3e;">${new Date().toLocaleString('id-ID')}</div>
            <div style="font-size:11px;color:#5c3d1e;margin-top:3px;">${trx.table ? '🪑 '+trx.table.name : '🥡 Takeaway'}</div>
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:10px;">
            ${(trx.items||[]).map(i=>`
            <div style="display:flex;justify-content:space-between;gap:8px;font-size:12px;">
                <div style="flex:1;"><div style="font-weight:600;color:#2e1d0e;">${escHtml(i.menu_name)}</div><div style="color:#a06c3e;font-size:10.5px;">${i.quantity} × Rp ${fmt(i.price)}</div></div>
                <div style="font-weight:600;color:#2e1d0e;white-space:nowrap;">Rp ${fmt(i.subtotal)}</div>
            </div>`).join('')}
        </div>
        <div style="border-top:1px dashed #f5deb3;padding-top:8px;display:flex;flex-direction:column;gap:4px;font-size:12px;">
            <div style="display:flex;justify-content:space-between;color:#5c3d1e;"><span>Subtotal</span><span>Rp ${fmt(trx.subtotal)}</span></div>
            ${trx.discount>0?`<div style="display:flex;justify-content:space-between;color:#ef4444;"><span>Diskon</span><span>- Rp ${fmt(trx.discount)}</span></div>`:''}
            <div style="display:flex;justify-content:space-between;font-weight:700;font-size:14px;color:#2e1d0e;"><span>TOTAL</span><span>Rp ${fmt(trx.total)}</span></div>
            <div style="display:flex;justify-content:space-between;color:#a06c3e;"><span>Bayar (${(trx.payment_method||'').toUpperCase()})</span><span>Rp ${fmt(trx.paid_amount)}</span></div>
            <div style="display:flex;justify-content:space-between;color:#166534;font-weight:600;"><span>Kembalian</span><span>Rp ${fmt(change)}</span></div>
        </div>
        <div style="text-align:center;font-size:11px;color:#d4b08a;margin-top:10px;padding-top:8px;border-top:1px dashed #f5deb3;">Terima kasih sudah berkunjung ☕</div>`;
    document.getElementById('receipt-modal').classList.remove('hidden');
}
function closeReceipt()   { document.getElementById('receipt-modal').classList.add('hidden'); resetSession(); }
function printReceipt()   { if(S.trx) window.open(`/pos/transaction/${S.trx.id}/print`,'_blank'); }
function newTransaction() { document.getElementById('receipt-modal').classList.add('hidden'); resetSession(); }

// ════════════════════════════════
//  RESET
// ════════════════════════════════
function resetSession() {
    S.trx = null; S.tableId = null; S.tableName = null;
    S.npItemId = null; S.npValue = ''; S.paymentMethod = null;
    document.getElementById('cart-items').querySelectorAll('.cart-item').forEach(el=>el.remove());
    document.getElementById('cart-empty').style.display = 'flex';
    document.getElementById('discount-input').value = '0';
    hideTableIndicator(); updateTrxPill(); recalcDisplay(); setNpContext(null); updateNpDisplay();
}

function fmt(n) { return parseFloat(n||0).toLocaleString('id-ID'); }
</script>
</body>
</html>
