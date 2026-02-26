<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Kopi Brader</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
<style>
:root {
    --bg: #0a0a0f; --surface: #111118; --surface2: #1a1a24;
    --border: #2a2a3a; --text: #f0f0f5; --muted: #6b6b7e;
    --accent: #d4ff00; --green: #22c55e; --red: #ef4444;
    --purple: #7c6aff; --yellow: #ffc800;
}
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Space Grotesk',sans-serif; background:var(--bg); color:var(--text); display:flex; min-height:100vh; overflow:hidden; }

/* ===== SIDEBAR ===== */
.sidebar {
    width:220px; flex-shrink:0; background:var(--surface);
    border-right:1px solid var(--border); display:flex; flex-direction:column;
    height:100vh; position:fixed; left:0; top:0; z-index:100;
}
.brand {
    padding:20px; border-bottom:1px solid var(--border);
    display:flex; align-items:center; gap:10px;
}
.brand-logo { font-size:1.4rem; }
.brand-name { font-family:'Syne',sans-serif; font-weight:800; font-size:1rem; }
.live-dot { width:8px; height:8px; background:var(--green); border-radius:50%; margin-left:auto; animation:blink 2s infinite; }
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

.nav-group { padding:12px 0; border-bottom:1px solid var(--border); }
.nav-label { padding:6px 16px 4px; font-size:0.62rem; color:var(--muted); text-transform:uppercase; letter-spacing:1px; }
.nav-item {
    display:flex; align-items:center; gap:10px; padding:10px 16px;
    cursor:pointer; transition:all 0.2s; font-size:0.84rem; color:var(--muted);
    border-left:3px solid transparent; position:relative; user-select:none;
}
.nav-item:hover { background:var(--surface2); color:var(--text); }
.nav-item.active { background:var(--surface2); color:var(--accent); border-left-color:var(--accent); font-weight:600; }
.nav-icon { font-size:1rem; width:20px; text-align:center; }
.nav-badge {
    margin-left:auto; background:var(--red); color:white;
    font-size:0.6rem; font-weight:700; padding:2px 6px;
    border-radius:100px; min-width:18px; text-align:center;
}

.sidebar-footer { margin-top:auto; padding:16px; border-top:1px solid var(--border); }
.footer-info { font-size:0.7rem; color:var(--muted); text-align:center; line-height:1.6; }

/* ===== MAIN ===== */
.main { margin-left:220px; flex:1; height:100vh; overflow-y:auto; }

/* ===== TOPBAR ===== */
.topbar {
    position:sticky; top:0; z-index:50;
    background:rgba(10,10,15,.95); backdrop-filter:blur(20px);
    border-bottom:1px solid var(--border); padding:14px 24px;
    display:flex; align-items:center; justify-content:space-between;
}
.topbar-title { font-family:'Syne',sans-serif; font-weight:800; font-size:1rem; }
.topbar-right { display:flex; align-items:center; gap:10px; }
.topbar-time { font-family:'JetBrains Mono',monospace; font-size:0.72rem; color:var(--muted); }
.preview-btn {
    background:var(--surface2); border:1px solid var(--border); color:var(--text);
    padding:6px 14px; border-radius:8px; font-size:0.78rem; cursor:pointer;
    transition:all 0.2s; text-decoration:none; display:flex; align-items:center; gap:6px;
}
.preview-btn:hover { border-color:var(--accent); color:var(--accent); }

/* ===== PAGES ===== */
.page { display:none; padding:24px; }
.page.active { display:block; }

/* ===== STATS ===== */
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
.stat-card { background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:16px; }
.stat-label { font-size:0.7rem; color:var(--muted); margin-bottom:6px; }
.stat-val { font-family:'Syne',sans-serif; font-size:1.6rem; font-weight:800; }
.stat-sub { font-size:0.68rem; color:var(--muted); margin-top:4px; }

/* ===== CARDS ===== */
.card { background:var(--surface); border:1px solid var(--border); border-radius:16px; overflow:hidden; margin-bottom:20px; }
.card-head { padding:14px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.card-title { font-family:'Syne',sans-serif; font-weight:800; font-size:0.95rem; }
.btn-sm { background:var(--surface2); border:1px solid var(--border); color:var(--text); padding:6px 14px; border-radius:8px; font-size:0.75rem; cursor:pointer; transition:all 0.2s; }
.btn-sm:hover { border-color:var(--accent); color:var(--accent); }
.btn-accent { background:var(--accent); border:none; color:#000; font-weight:700; padding:8px 16px; border-radius:8px; font-size:0.8rem; cursor:pointer; transition:all 0.2s; }

/* ===== TABLE ===== */
table { width:100%; border-collapse:collapse; }
th { padding:10px 16px; text-align:left; font-size:0.68rem; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid var(--border); background:var(--surface2); white-space:nowrap; }
td { padding:12px 16px; border-bottom:1px solid var(--border); font-size:0.83rem; }
tr:last-child td { border-bottom:none; }
tr:hover td { background:rgba(255,255,255,.012); }

/* ===== STATUS BADGE ===== */
.sbadge { display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:100px; font-size:0.68rem; font-weight:700; cursor:pointer; transition:all 0.2s; white-space:nowrap; }
.sbadge:hover { opacity:.8; transform:scale(1.04); }
.sb-pending { background:rgba(255,200,0,.15); color:var(--yellow); }
.sb-process { background:rgba(124,106,255,.15); color:var(--purple); }
.sb-done    { background:rgba(34,197,94,.15); color:var(--green); }
.sb-cancel  { background:rgba(239,68,68,.15); color:var(--red); }

.meja-tag { font-family:'JetBrains Mono',monospace; font-size:0.72rem; background:var(--surface2); padding:3px 8px; border-radius:6px; }
.action-btn { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:var(--red); width:28px; height:28px; border-radius:6px; cursor:pointer; font-size:0.8rem; display:inline-flex; align-items:center; justify-content:center; transition:all .2s; }
.action-btn:hover { background:rgba(239,68,68,.25); }

.empty-state { text-align:center; padding:48px; color:var(--muted); }
.empty-icon { font-size:3rem; margin-bottom:10px; }

/* ===== MENU GRID ===== */
.menu-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:12px; padding:16px; }
.menu-card { background:var(--surface2); border:1px solid var(--border); border-radius:12px; padding:12px; transition:border-color .2s; }
.menu-card:hover { border-color:rgba(255,255,255,.1); }
.mc-top { display:flex; gap:8px; margin-bottom:8px; }
.mc-thumb { width:44px; height:44px; border-radius:8px; background:var(--surface); display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.mc-name { font-weight:700; font-size:0.84rem; margin-bottom:2px; }
.mc-cat { font-size:0.62rem; color:var(--muted); text-transform:uppercase; }
.mc-price { font-family:'Syne',sans-serif; font-size:0.95rem; font-weight:800; color:var(--accent); margin-bottom:8px; }
.mc-desc { font-size:0.7rem; color:var(--muted); margin-bottom:8px; line-height:1.4; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; }
.mc-actions { display:flex; gap:6px; }
.mc-edit { flex:1; background:var(--surface); border:1px solid var(--border); color:var(--text); padding:5px; border-radius:6px; cursor:pointer; font-size:0.72rem; text-align:center; transition:all .2s; }
.mc-edit:hover { border-color:var(--accent); color:var(--accent); }
.mc-del { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.2); color:var(--red); padding:5px 9px; border-radius:6px; cursor:pointer; font-size:0.72rem; transition:all .2s; }
.toggle { width:32px; height:18px; background:var(--surface); border-radius:100px; position:relative; cursor:pointer; transition:background .2s; border:1px solid var(--border); flex-shrink:0; }
.toggle.on { background:var(--green); border-color:var(--green); }
.toggle::after { content:''; position:absolute; width:12px; height:12px; background:white; border-radius:50%; top:2px; left:2px; transition:left .2s; }
.toggle.on::after { left:16px; }
.mc-footer { display:flex; align-items:center; gap:6px; font-size:0.68rem; color:var(--muted); margin-top:6px; }

/* ===== LAPORAN ===== */
.filter-bar { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; }
.filter-group { display:flex; flex-direction:column; gap:4px; }
.filter-label { font-size:0.68rem; color:var(--muted); }
.filter-input, .filter-select { background:var(--surface2); border:1px solid var(--border); border-radius:8px; padding:7px 10px; color:var(--text); font-family:'Space Grotesk',sans-serif; font-size:0.8rem; outline:none; transition:border-color .2s; }
.filter-input:focus, .filter-select:focus { border-color:var(--accent); }
.filter-select option { background:#1a1a2e; }

.export-row { display:flex; gap:10px; padding:14px 20px; border-bottom:1px solid var(--border); }
.export-btn { display:flex; align-items:center; gap:6px; border:none; padding:8px 16px; border-radius:8px; cursor:pointer; font-size:0.78rem; font-weight:700; transition:all .2s; }
.export-excel { background:rgba(34,197,94,.15); color:var(--green); border:1px solid rgba(34,197,94,.25); }
.export-excel:hover { background:rgba(34,197,94,.25); }
.export-pdf { background:rgba(239,68,68,.15); color:var(--red); border:1px solid rgba(239,68,68,.25); }
.export-pdf:hover { background:rgba(239,68,68,.25); }

.lap-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; padding:16px 20px; border-bottom:1px solid var(--border); }

/* CHART */
.chart-wrap { padding:16px 20px; border-bottom:1px solid var(--border); }
.chart-title { font-family:'Syne',sans-serif; font-weight:800; font-size:0.85rem; margin-bottom:12px; }
.bar-chart { display:flex; align-items:flex-end; gap:8px; height:100px; }
.bar-col { flex:1; display:flex; flex-direction:column; align-items:center; gap:4px; height:100%; justify-content:flex-end; }
.bar { width:100%; background:var(--accent); border-radius:4px 4px 0 0; min-height:4px; opacity:.85; transition:height .5s; }
.bar-label { font-family:'JetBrains Mono',monospace; font-size:0.58rem; color:var(--muted); }

/* ===== STATUS PAGE ===== */
.status-search { padding:20px; border-bottom:1px solid var(--border); display:flex; gap:10px; }
.status-input { flex:1; background:var(--surface2); border:1px solid var(--border); border-radius:10px; padding:10px 14px; color:var(--text); font-family:'JetBrains Mono',monospace; font-size:0.88rem; outline:none; transition:border-color .2s; }
.status-input:focus { border-color:var(--accent); }
.status-result { padding:20px; }

.step-row { display:flex; gap:12px; align-items:flex-start; padding-bottom:18px; position:relative; }
.step-row:last-child { padding-bottom:0; }
.step-row:not(:last-child)::before { content:''; position:absolute; left:14px; top:30px; width:2px; height:calc(100% - 10px); background:var(--border); }
.step-row.done::before { background:var(--accent); }
.step-dot { width:30px; height:30px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:0.85rem; border:2px solid var(--border); background:var(--surface2); z-index:1; }
.step-row.done .step-dot { background:var(--accent); border-color:var(--accent); }
.step-row.active .step-dot { background:var(--accent); border-color:var(--accent); box-shadow:0 0 0 4px rgba(212,255,0,.2); animation:pulseStep 1.5s infinite; }
@keyframes pulseStep { 0%,100%{box-shadow:0 0 0 4px rgba(212,255,0,.2)} 50%{box-shadow:0 0 0 8px rgba(212,255,0,.05)} }
.step-title { font-weight:700; font-size:0.85rem; margin-bottom:2px; }
.step-row.done .step-title { color:var(--text); }
.step-row.active .step-title { color:var(--accent); }
.step-row:not(.done):not(.active) .step-title { color:var(--muted); }
.step-desc { font-size:0.72rem; color:var(--muted); }

/* ===== MODAL ===== */
.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.7); display:none; align-items:center; justify-content:center; z-index:999; backdrop-filter:blur(8px); padding:20px; }
.modal-overlay.open { display:flex; }
.modal { background:var(--surface); border:1px solid var(--border); border-radius:20px; padding:24px; width:100%; max-width:400px; }
.modal-title { font-family:'Syne',sans-serif; font-size:1rem; font-weight:800; margin-bottom:18px; }
.form-group { margin-bottom:12px; }
.form-label { font-size:0.72rem; color:var(--muted); margin-bottom:5px; display:block; }
.form-input, .form-select, .form-textarea { width:100%; background:var(--surface2); border:1px solid var(--border); border-radius:8px; padding:9px 12px; color:var(--text); font-family:'Space Grotesk',sans-serif; font-size:0.83rem; outline:none; transition:border-color .2s; }
.form-input:focus, .form-select:focus, .form-textarea:focus { border-color:var(--accent); }
.form-select option { background:#1a1a2e; }
.form-textarea { resize:none; min-height:72px; }
.modal-actions { display:flex; gap:10px; margin-top:16px; }
.modal-save { flex:1; background:var(--accent); color:#000; font-weight:700; border:none; padding:11px; border-radius:8px; cursor:pointer; font-size:0.88rem; }
.modal-cancel { background:var(--surface2); border:1px solid var(--border); color:var(--text); padding:11px 18px; border-radius:8px; cursor:pointer; font-size:0.83rem; }

/* ===== TOAST ===== */
.toast { position:fixed; top:20px; right:20px; z-index:9999; background:var(--surface); border:1px solid var(--accent); border-radius:14px; padding:14px 18px; display:none; align-items:center; gap:12px; box-shadow:0 8px 32px rgba(0,0,0,.5); min-width:260px; animation:slideIn .3s ease; }
.toast.show { display:flex; }
@keyframes slideIn { from{transform:translateX(120%)} to{transform:translateX(0)} }
</style>
</head>
<body>

{{-- TOAST --}}
<div class="toast" id="toast">
    <div style="font-size:1.8rem" id="toastIcon">🔔</div>
    <div>
        <div style="font-weight:700;font-size:0.86rem" id="toastTitle">Order Baru!</div>
        <div style="font-size:0.72rem;color:var(--muted)" id="toastSub"></div>
    </div>
</div>

{{-- SIDEBAR --}}
<div class="sidebar">
    <div class="brand">
        <div class="brand-logo">☕</div>
        <div class="brand-name">Kopi Brader</div>
        <div class="live-dot"></div>
    </div>

    <div class="nav-group">
        <div class="nav-label">Pesanan</div>
        <div class="nav-item active" onclick="showPage('orders')">
            <span class="nav-icon">📋</span> Order Masuk
            <span class="nav-badge" id="pendingBadge" style="display:none">0</span>
        </div>
    </div>

    <div class="nav-group">
        <div class="nav-label">Manajemen</div>
        <div class="nav-item" onclick="showPage('menu')">
            <span class="nav-icon">🍽️</span> Kelola Menu
        </div>
        <div class="nav-item" onclick="showPage('laporan')">
            <span class="nav-icon">📊</span> Laporan
        </div>
        <div class="nav-item" onclick="showPage('status')">
            <span class="nav-icon">🔍</span> Cek Status Order
        </div>
    </div>

    <div class="nav-group">
        <div class="nav-label">Aksi</div>
        <div class="nav-item" onclick="window.open('/s/01','_blank')">
            <span class="nav-icon">👁️</span> Preview Menu
        </div>
        <div class="nav-item" onclick="window.open('/admin','_blank')">
            <span class="nav-icon">⚙️</span> Filament Admin
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="footer-info">Kopi Brader v1.0<br>Auto refresh 15 detik</div>
    </div>
</div>

{{-- MAIN --}}
<div class="main">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-title" id="pageTitle">📋 Order Masuk</div>
        <div class="topbar-right">
            <div class="topbar-time" id="clock"></div>
            <a href="#" class="preview-btn" onclick="window.open('/s/01','_blank')">
                👁️ Preview Menu
            </a>
        </div>
    </div>

    {{-- PAGE: ORDERS --}}
    <div class="page active" id="page-orders">
        <div class="stats-grid">
            <div class="stat-card" style="border-color:rgba(34,197,94,.2)">
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-val" id="sRevenue" style="color:var(--green)">Rp 0</div>
                <div class="stat-sub">Order selesai</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Order</div>
                <div class="stat-val" id="sOrders">0</div>
                <div class="stat-sub">Hari ini</div>
            </div>
            <div class="stat-card" style="border-color:rgba(212,255,0,.15)">
                <div class="stat-label">Pending</div>
                <div class="stat-val" id="sPending" style="color:var(--accent)">0</div>
                <div class="stat-sub">Belum diproses</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Selesai</div>
                <div class="stat-val" id="sDone">0</div>
                <div class="stat-sub">Done hari ini</div>
            </div>
        </div>
        <div class="card">
            <div class="card-head">
                <div class="card-title">Semua Order</div>
                <button class="btn-sm" onclick="loadOrders()">🔄 Refresh</button>
            </div>
            <div id="ordersBody"><div class="empty-state"><div class="empty-icon">⏳</div>Loading...</div></div>
        </div>
    </div>

    {{-- PAGE: MENU --}}
    <div class="page" id="page-menu">
        <div class="card">
            <div class="card-head">
                <div class="card-title">🍽️ Kelola Menu</div>
                <button class="btn-accent" onclick="openMenuModal()">+ Tambah Menu</button>
            </div>
            <div class="menu-grid" id="menuGrid">
                <div class="empty-state" style="grid-column:1/-1">⏳ Loading...</div>
            </div>
        </div>
    </div>

    {{-- PAGE: LAPORAN --}}
    <div class="page" id="page-laporan">
        <div class="card">
            <div class="card-head">
                <div class="card-title">📊 Laporan Penjualan</div>
            </div>
            <div class="filter-bar">
                <div class="filter-group">
                    <label class="filter-label">Dari</label>
                    <input type="date" class="filter-input" id="lapFrom">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Sampai</label>
                    <input type="date" class="filter-input" id="lapTo">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-select" id="lapStatus">
                        <option value="">Semua</option>
                        <option value="done">Selesai</option>
                        <option value="pending">Pending</option>
                        <option value="process">Proses</option>
                        <option value="cancel">Batal</option>
                    </select>
                </div>
                <button class="btn-accent" onclick="loadLaporan()">🔍 Filter</button>
            </div>
            <div class="lap-stats">
                <div class="stat-card" style="border-color:rgba(34,197,94,.2)">
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-val" id="lapTotal" style="color:var(--green);font-size:1.2rem">Rp 0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Order</div>
                    <div class="stat-val" id="lapCount" style="font-size:1.2rem">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Order Selesai</div>
                    <div class="stat-val" id="lapDone" style="color:var(--accent);font-size:1.2rem">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Rata-rata/Order</div>
                    <div class="stat-val" id="lapAvg" style="font-size:1.2rem">Rp 0</div>
                </div>
            </div>
            <div class="chart-wrap">
                <div class="chart-title">📈 7 Hari Terakhir</div>
                <div class="bar-chart" id="barChart"></div>
            </div>
            <div class="export-row">
                <button class="export-btn export-excel" onclick="exportExcel()">📊 Export Excel</button>
                <button class="export-btn export-pdf" onclick="exportPDF()">📄 Export PDF</button>
            </div>
            <div id="laporanBody"><div class="empty-state">⏳ Loading...</div></div>
        </div>
    </div>

    {{-- PAGE: STATUS --}}
    <div class="page" id="page-status">
        <div class="card">
            <div class="card-head">
                <div class="card-title">🔍 Cek Status Order</div>
            </div>
            <div class="status-search">
                <input class="status-input" type="number" id="statusInput" placeholder="Masukkan nomor order... (contoh: 3)" min="1">
                <button class="btn-accent" onclick="cekStatus()">🔍 Cek</button>
            </div>
            <div class="status-result" id="statusResult">
                <div class="empty-state"><div class="empty-icon">🔍</div>Masukkan nomor order untuk cek status</div>
            </div>
        </div>
    </div>

</div>{{-- end main --}}

{{-- MODAL MENU --}}
<div class="modal-overlay" id="menuModal">
    <div class="modal">
        <div class="modal-title" id="modalTitle">➕ Tambah Menu</div>
        <input type="hidden" id="editId">
        <div class="form-group">
            <label class="form-label">Nama Menu</label>
            <input class="form-input" id="fName" placeholder="Kopi Susu Brader">
        </div>
        <div class="form-group">
            <label class="form-label">Kategori</label>
            <select class="form-select" id="fCat">
                <option value="coffee">☕ Kopi</option>
                <option value="noncoffee">🧋 Non-Kopi</option>
                <option value="food">🍞 Makanan</option>
                <option value="snack">🍿 Snack</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Harga (Rp)</label>
            <input class="form-input" id="fPrice" type="number" placeholder="28000">
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-textarea" id="fDesc" placeholder="Deskripsi singkat..."></textarea>
        </div>
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Batal</button>
            <button class="modal-save" onclick="saveMenu()">💾 Simpan</button>
        </div>
    </div>
</div>

<script>
const CSRF  = document.querySelector('meta[name="csrf-token"]').content;
const EMOJI = { coffee:'☕', noncoffee:'🧋', food:'🍞', snack:'🍿' };
const SL    = { pending:'⏳ Pending', process:'🔄 Proses', done:'✅ Done', cancel:'❌ Batal' };
const SC    = { pending:'sb-pending', process:'sb-process', done:'sb-done', cancel:'sb-cancel' };
const SN    = { pending:'process', process:'done', done:'done', cancel:'cancel' };
const PAGES = { orders:'📋 Order Masuk', menu:'🍽️ Kelola Menu', laporan:'📊 Laporan', status:'🔍 Cek Status Order' };

let orders=[], menuItems=[], lastCount=0;

function fmt(n){ return 'Rp '+parseInt(n).toLocaleString('id-ID'); }

// CLOCK
function updateClock(){
    const now=new Date();
    document.getElementById('clock').textContent=now.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(updateClock,1000); updateClock();

// SWITCH PAGE
function showPage(name){
    document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
    document.getElementById('page-'+name).classList.add('active');
    event.currentTarget.classList.add('active');
    document.getElementById('pageTitle').textContent = PAGES[name]||name;
    if(name==='orders')  loadOrders();
    if(name==='menu')    loadMenu();
    if(name==='laporan') loadLaporan();
}

// ===== ORDERS =====
async function loadOrders(){
    try{
        const r=await fetch('/admin-api/orders',{headers:{'Accept':'application/json'}});
        orders=await r.json();
        renderOrders(); updateStats(); checkNew();
    }catch(e){
        document.getElementById('ordersBody').innerHTML='<div class="empty-state"><div class="empty-icon">❌</div>Gagal load</div>';
    }
}

function renderOrders(){
    if(!orders.length){
        document.getElementById('ordersBody').innerHTML='<div class="empty-state"><div class="empty-icon">📭</div>Belum ada order</div>';
        return;
    }
    document.getElementById('ordersBody').innerHTML=`
    <table><thead><tr>
        <th>#</th><th>Meja</th><th>Items</th><th>Total</th><th>Bayar</th><th>Status</th><th>Waktu</th><th>Aksi</th>
    </tr></thead><tbody>
    ${orders.map(o=>`<tr>
        <td><span style="font-family:monospace;color:var(--muted)">#${String(o.id).padStart(4,'0')}</span></td>
        <td><span class="meja-tag">Meja ${o.table?.table_number||'-'}</span></td>
        <td style="max-width:180px;font-size:0.75rem;color:var(--muted)">${(o.order_items||[]).map(i=>(i.product?.name||'?')+' x'+i.quantity).join(', ')}</td>
        <td style="font-weight:700;color:var(--accent)">${fmt(o.total_price)}</td>
        <td style="font-size:0.75rem;text-transform:uppercase">${o.payment_method}</td>
        <td><span class="sbadge ${SC[o.status]}" onclick="nextStatus(${o.id},'${o.status}')" title="Klik update status">${SL[o.status]||o.status}</span></td>
        <td style="font-size:0.72rem;color:var(--muted)">${new Date(o.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</td>
        <td><button class="action-btn" onclick="cancelOrder(${o.id})" title="Cancel">✖</button></td>
    </tr>`).join('')}
    </tbody></table>`;
}

function updateStats(){
    const today=new Date().toDateString();
    const tod=orders.filter(o=>new Date(o.created_at).toDateString()===today);
    const pend=orders.filter(o=>o.status==='pending').length;
    const done=tod.filter(o=>o.status==='done').length;
    const rev=tod.filter(o=>o.status==='done').reduce((s,o)=>s+parseInt(o.total_price),0);
    document.getElementById('sRevenue').textContent=fmt(rev);
    document.getElementById('sOrders').textContent=tod.length;
    document.getElementById('sPending').textContent=pend;
    document.getElementById('sDone').textContent=done;
    const b=document.getElementById('pendingBadge');
    b.textContent=pend; b.style.display=pend>0?'inline-block':'none';
}

function checkNew(){
    if(orders.length>lastCount&&lastCount>0){
        const o=orders[0];
        showToast('🔔','Order Baru! Meja '+o.table?.table_number,fmt(o.total_price)+' · '+o.payment_method.toUpperCase());
        beep();
    }
    lastCount=orders.length;
}

async function nextStatus(id,cur){
    const next=SN[cur]; if(!next||next===cur) return;
    await fetch(`/admin-api/orders/${id}/status`,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify({status:next})});
    loadOrders();
}

async function cancelOrder(id){
    if(!confirm('Cancel order ini?')) return;
    await fetch(`/admin-api/orders/${id}/status`,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify({status:'cancel'})});
    loadOrders();
}

// ===== MENU =====
async function loadMenu(){
    try{
        const r=await fetch('/admin-api/products',{headers:{'Accept':'application/json'}});
        menuItems=await r.json(); renderMenu();
    }catch(e){}
}

function renderMenu(){
    if(!menuItems.length){document.getElementById('menuGrid').innerHTML='<div class="empty-state" style="grid-column:1/-1">🍽️ Belum ada menu</div>';return;}
    document.getElementById('menuGrid').innerHTML=menuItems.map(m=>`
    <div class="menu-card">
        <div class="mc-top">
            <div class="mc-thumb">${m.image
            ? `<img src="/images/${m.image}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">`
            : (EMOJI[m.category]||'☕')}</div>
            <div><div class="mc-name">${m.name}</div><div class="mc-cat">${m.category}</div></div>
        </div>
        <div class="mc-price">${fmt(m.price)}</div>
        <div class="mc-desc">${m.description||'-'}</div>
        <div class="mc-footer">
            <div class="toggle ${m.is_ready?'on':''}" onclick="toggleReady(${m.id},${m.is_ready})"></div>
            <span>${m.is_ready?'Tersedia':'Habis'}</span>
        </div>
        <div class="mc-actions" style="margin-top:8px">
            <button class="mc-edit" onclick="openMenuModal(${m.id})">✏️ Edit</button>
            <button class="mc-del" onclick="deleteMenu(${m.id})">🗑️</button>
        </div>
    </div>`).join('');
}

function openMenuModal(id=null){
    const m=id?menuItems.find(x=>x.id===id):null;
    document.getElementById('modalTitle').textContent=m?'✏️ Edit Menu':'➕ Tambah Menu';
    document.getElementById('editId').value=m?.id||'';
    document.getElementById('fName').value=m?.name||'';
    document.getElementById('fCat').value=m?.category||'coffee';
    document.getElementById('fPrice').value=m?.price||'';
    document.getElementById('fDesc').value=m?.description||'';
    document.getElementById('menuModal').classList.add('open');
}
function closeModal(){ document.getElementById('menuModal').classList.remove('open'); }

async function saveMenu(){
    const id=document.getElementById('editId').value;
    const data={name:document.getElementById('fName').value,category:document.getElementById('fCat').value,price:parseInt(document.getElementById('fPrice').value),description:document.getElementById('fDesc').value};
    await fetch(id?`/admin-api/products/${id}`:'/admin-api/products',{method:id?'PUT':'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify(data)});
    closeModal(); loadMenu();
}

async function deleteMenu(id){
    if(!confirm('Hapus menu ini?')) return;
    await fetch(`/admin-api/products/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}});
    loadMenu();
}

async function toggleReady(id,cur){
    await fetch(`/admin-api/products/${id}`,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},body:JSON.stringify({is_ready:!cur})});
    loadMenu();
}

// ===== LAPORAN =====
function initLaporan(){
    const today=new Date(), week=new Date(today);
    week.setDate(week.getDate()-7);
    document.getElementById('lapFrom').value=week.toISOString().slice(0,10);
    document.getElementById('lapTo').value=today.toISOString().slice(0,10);
}

async function loadLaporan(){
    const from=document.getElementById('lapFrom').value;
    const to=document.getElementById('lapTo').value;
    const status=document.getElementById('lapStatus').value;
    try{
        const r=await fetch(`/admin-api/laporan?from=${from}&to=${to}&status=${status}`,{headers:{'Accept':'application/json'}});
        const data=await r.json();
        updateLapStats(data.orders||[]);
        renderLapTable(data.orders||[]);
        renderChart(data.chart||[]);
    }catch(e){}
}

function updateLapStats(orders){
    const done=orders.filter(o=>o.status==='done');
    const total=done.reduce((s,o)=>s+parseInt(o.total_price),0);
    const avg=done.length?Math.round(total/done.length):0;
    document.getElementById('lapTotal').textContent=fmt(total);
    document.getElementById('lapCount').textContent=orders.length;
    document.getElementById('lapDone').textContent=done.length;
    document.getElementById('lapAvg').textContent=fmt(avg);
}

function renderLapTable(orders){
    if(!orders.length){document.getElementById('laporanBody').innerHTML='<div class="empty-state">📭 Tidak ada data</div>';return;}
    document.getElementById('laporanBody').innerHTML=`
    <table><thead><tr><th>#Order</th><th>Meja</th><th>Items</th><th>Total</th><th>Bayar</th><th>Status</th><th>Waktu</th></tr></thead><tbody>
    ${orders.map(o=>`<tr>
        <td style="font-family:monospace;color:var(--muted)">#${String(o.id).padStart(4,'0')}</td>
        <td>Meja ${o.table?.table_number||'-'}</td>
        <td style="font-size:0.72rem;color:var(--muted);max-width:180px">${(o.order_items||[]).map(i=>(i.product?.name||'?')+'x'+i.quantity).join(', ')}</td>
        <td style="color:var(--accent);font-weight:700">${fmt(o.total_price)}</td>
        <td style="text-transform:uppercase;font-size:0.75rem">${o.payment_method}</td>
        <td><span class="sbadge ${SC[o.status]}">${SL[o.status]||o.status}</span></td>
        <td style="font-size:0.72rem;color:var(--muted)">${new Date(o.created_at).toLocaleString('id-ID',{day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'})}</td>
    </tr>`).join('')}
    </tbody></table>`;
}

function renderChart(data){
    if(!data.length){document.getElementById('barChart').innerHTML='<div style="color:var(--muted);font-size:0.8rem;text-align:center;width:100%">Belum ada data</div>';return;}
    const max=Math.max(...data.map(d=>d.total),1);
    document.getElementById('barChart').innerHTML=data.map(d=>`
    <div class="bar-col">
        <div class="bar" style="height:${Math.max((d.total/max)*100,4)}%" title="${fmt(d.total)}"></div>
        <div class="bar-label">${d.label}</div>
    </div>`).join('');
}

function exportExcel(){ window.location.href=`/admin-api/laporan/export-excel?from=${document.getElementById('lapFrom').value}&to=${document.getElementById('lapTo').value}&status=${document.getElementById('lapStatus').value}`; }
function exportPDF(){   window.location.href=`/admin-api/laporan/export-pdf?from=${document.getElementById('lapFrom').value}&to=${document.getElementById('lapTo').value}&status=${document.getElementById('lapStatus').value}`; }

// ===== CEK STATUS =====
async function cekStatus(){
    const id=document.getElementById('statusInput').value.trim();
    if(!id){alert('Masukkan nomor order!');return;}
    try{
        const r=await fetch(`/status/order/${id}`,{headers:{'Accept':'application/json'}});
        const data=await r.json();
        if(!data.success){
            document.getElementById('statusResult').innerHTML='<div class="empty-state"><div class="empty-icon">🔍</div>Order #'+id+' tidak ditemukan</div>';
            return;
        }
        renderStatus(data.order);
    }catch(e){alert('Error, coba lagi.');}
}

const STEP_CFG={
    pending:[{icon:'📝',title:'Order Diterima',desc:'Pesanan masuk ke sistem',state:'done'},{icon:'👨‍🍳',title:'Sedang Diproses',desc:'Barista menyiapkan pesanan',state:'waiting'},{icon:'✅',title:'Siap Disajikan',desc:'Pesanan siap diantar',state:'waiting'}],
    process:[{icon:'📝',title:'Order Diterima',desc:'Pesanan masuk ke sistem',state:'done'},{icon:'👨‍🍳',title:'Sedang Diproses',desc:'Barista sedang menyiapkan ☕',state:'active'},{icon:'✅',title:'Siap Disajikan',desc:'Pesanan siap diantar',state:'waiting'}],
    done:   [{icon:'📝',title:'Order Diterima',desc:'Pesanan masuk ke sistem',state:'done'},{icon:'👨‍🍳',title:'Diproses',desc:'Barista sudah menyiapkan',state:'done'},{icon:'✅',title:'Selesai!',desc:'Selamat menikmati! ☕',state:'active'}],
    cancel: [{icon:'📝',title:'Order Diterima',desc:'Pesanan masuk ke sistem',state:'done'},{icon:'❌',title:'Dibatalkan',desc:'Order ini telah dibatalkan',state:'active'}],
};

function renderStatus(order){
    const oid=String(order.id).padStart(4,'0');
    const steps=STEP_CFG[order.status]||STEP_CFG.pending;
    document.getElementById('statusResult').innerHTML=`
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div>
            <div style="margin-bottom:12px;padding:14px;background:var(--surface2);border:1px solid var(--border);border-radius:12px;">
                <div style="font-family:monospace;font-size:0.75rem;color:var(--accent);margin-bottom:6px"># ORDER-${oid}</div>
                <div style="font-family:'Syne',sans-serif;font-size:1.4rem;font-weight:800;color:var(--accent)">${fmt(order.total_price)}</div>
                <div style="font-size:0.72rem;color:var(--muted);margin-top:4px">Meja ${order.table?.table_number||'-'} · ${order.payment_method.toUpperCase()}</div>
            </div>
            <div style="padding:14px;background:var(--surface2);border:1px solid var(--border);border-radius:12px;">
                <div style="font-weight:700;font-size:0.85rem;margin-bottom:10px">📋 Detail Pesanan</div>
                ${(order.order_items||[]).map(i=>`<div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);font-size:0.8rem"><span>${i.product?.name||'?'} <span style="color:var(--muted)">x${i.quantity}</span></span><span style="color:var(--accent)">${fmt(i.subtotal)}</span></div>`).join('')}
                <div style="display:flex;justify-content:space-between;padding:8px 0 0;font-weight:700"><span>Total</span><span style="color:var(--accent)">${fmt(order.total_price)}</span></div>
            </div>
        </div>
        <div style="padding:14px;background:var(--surface2);border:1px solid var(--border);border-radius:12px;">
            <div style="font-weight:700;font-size:0.85rem;margin-bottom:14px">📍 Status Real-time</div>
            ${steps.map(s=>`<div class="step-row ${s.state}"><div class="step-dot">${s.icon}</div><div><div class="step-title">${s.title}</div><div class="step-desc">${s.desc}</div></div></div>`).join('')}
        </div>
    </div>`;
}

// TOAST
function showToast(icon,title,sub){
    document.getElementById('toastIcon').textContent=icon;
    document.getElementById('toastTitle').textContent=title;
    document.getElementById('toastSub').textContent=sub;
    const t=document.getElementById('toast');
    t.classList.add('show');
    setTimeout(()=>t.classList.remove('show'),5000);
}

function beep(){
    try{
        const ctx=new AudioContext();
        [800,1000].forEach((f,i)=>{
            const o=ctx.createOscillator(),g=ctx.createGain();
            o.connect(g);g.connect(ctx.destination);
            o.frequency.value=f;g.gain.value=0.15;
            o.start(ctx.currentTime+i*0.2);
            o.stop(ctx.currentTime+i*0.2+0.15);
        });
    }catch(e){}
}

// INIT
initLaporan();
loadOrders();
setInterval(loadOrders,15000);
</script>
</body>
</html>