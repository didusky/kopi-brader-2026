@extends('layouts.app')
@section('title', 'Laporan Penjualan - Kopi Brader')

@push('styles')
<style>
    .laporan-header {
        padding: 20px 20px 0;
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 20px;
    }
    .back-btn {
        background: var(--surface); border: 1px solid var(--border);
        color: var(--text); width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 1.1rem;
    }
    .header-title { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 800; }

    .filter-card {
        margin: 0 20px 20px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; padding: 16px;
    }
    .filter-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; margin-bottom: 12px; }
    .filter-row { display: flex; gap: 10px; flex-wrap: wrap; }
    .filter-group { flex: 1; min-width: 140px; }
    .filter-label { font-size: 0.72rem; color: var(--muted); margin-bottom: 5px; display: block; }
    .filter-input, .filter-select {
        width: 100%; background: var(--surface2); border: 1px solid var(--border);
        border-radius: 8px; padding: 9px 12px; color: var(--text);
        font-family: 'Space Grotesk', sans-serif; font-size: 0.83rem; outline: none;
        transition: border-color 0.2s;
    }
    .filter-input:focus, .filter-select:focus { border-color: var(--accent); }
    .filter-select option { background: #1a1a2e; }
    .filter-btn {
        background: var(--accent); color: #000; font-weight: 700;
        border: none; padding: 9px 18px; border-radius: 8px; cursor: pointer;
        font-family: 'Space Grotesk', sans-serif; font-size: 0.83rem;
        align-self: flex-end; white-space: nowrap;
    }

    /* STATS */
    .stats-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;
        padding: 0 20px 20px;
    }
    .stat-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; padding: 14px;
    }
    .stat-label { font-size: 0.7rem; color: var(--muted); margin-bottom: 5px; }
    .stat-val { font-family: 'Syne', sans-serif; font-size: 1.3rem; font-weight: 800; }
    .stat-val.green { color: #22c55e; }
    .stat-val.yellow { color: var(--accent); }

    /* EXPORT BTNS */
    .export-row {
        display: flex; gap: 10px; padding: 0 20px 20px;
    }
    .export-btn {
        flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
        border: none; padding: 12px; border-radius: 12px; cursor: pointer;
        font-family: 'Space Grotesk', sans-serif; font-size: 0.85rem; font-weight: 700;
        transition: all 0.2s;
    }
    .export-excel { background: rgba(34,197,94,.15); color: #22c55e; border: 1px solid rgba(34,197,94,.25); }
    .export-excel:hover { background: rgba(34,197,94,.25); }
    .export-pdf { background: rgba(239,68,68,.15); color: #ef4444; border: 1px solid rgba(239,68,68,.25); }
    .export-pdf:hover { background: rgba(239,68,68,.25); }

    /* TABLE */
    .table-card {
        margin: 0 20px 20px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
    }
    .table-head {
        padding: 14px 20px; border-bottom: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; }
    .table-count { font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: var(--muted); }

    table { width: 100%; border-collapse: collapse; }
    th { padding: 10px 14px; text-align: left; font-size: 0.68rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); background: var(--surface2); white-space: nowrap; }
    td { padding: 11px 14px; border-bottom: 1px solid var(--border); font-size: 0.82rem; }
    tr:last-child td { border-bottom: none; }

    .sbadge { display: inline-flex; padding: 3px 8px; border-radius: 100px; font-size: 0.65rem; font-weight: 700; }
    .sb-pending  { background: rgba(255,200,0,.15); color: #ffc800; }
    .sb-process  { background: rgba(124,106,255,.15); color: #7c6aff; }
    .sb-done     { background: rgba(34,197,94,.15); color: #22c55e; }
    .sb-cancel   { background: rgba(239,68,68,.15); color: #ef4444; }

    .empty-state { text-align: center; padding: 40px; color: var(--muted); }

    /* CHART */
    .chart-card {
        margin: 0 20px 20px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; padding: 16px;
    }
    .chart-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; margin-bottom: 14px; }
    .bar-chart { display: flex; align-items: flex-end; gap: 6px; height: 120px; }
    .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; height: 100%; justify-content: flex-end; }
    .bar { width: 100%; background: var(--accent); border-radius: 4px 4px 0 0; min-height: 4px; transition: height 0.5s; opacity: 0.85; }
    .bar-label { font-family: 'JetBrains Mono', monospace; font-size: 0.6rem; color: var(--muted); white-space: nowrap; }

    @media (max-width: 600px) {
        th:nth-child(3), td:nth-child(3),
        th:nth-child(5), td:nth-child(5) { display: none; }
    }
</style>
@endpush

@section('content')

<div class="laporan-header">
    <a href="/dashboard" class="back-btn">←</a>
    <div class="header-title">📊 Laporan Penjualan</div>
</div>

<div class="filter-card">
    <div class="filter-title">🔎 Filter</div>
    <div class="filter-row">
        <div class="filter-group">
            <label class="filter-label">Dari Tanggal</label>
            <input type="date" class="filter-input" id="dateFrom">
        </div>
        <div class="filter-group">
            <label class="filter-label">Sampai Tanggal</label>
            <input type="date" class="filter-input" id="dateTo">
        </div>
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select class="filter-select" id="filterStatus">
                <option value="">Semua</option>
                <option value="pending">Pending</option>
                <option value="process">Proses</option>
                <option value="done">Selesai</option>
                <option value="cancel">Batal</option>
            </select>
        </div>
        <button class="filter-btn" onclick="loadLaporan()">🔍 Filter</button>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-val green" id="sTotal">Rp 0</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Order</div>
        <div class="stat-val" id="sCount">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Order Selesai</div>
        <div class="stat-val yellow" id="sDone">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Rata-rata/Order</div>
        <div class="stat-val" id="sAvg">Rp 0</div>
    </div>
</div>

<div class="export-row">
    <button class="export-btn export-excel" onclick="exportExcel()">
        📊 Export Excel
    </button>
    <button class="export-btn export-pdf" onclick="exportPDF()">
        📄 Export PDF
    </button>
</div>

<div class="chart-card">
    <div class="chart-title">📈 Penjualan 7 Hari Terakhir</div>
    <div class="bar-chart" id="barChart">
        <div class="empty-state" style="padding:20px;width:100%;">Loading chart...</div>
    </div>
</div>

<div class="table-card">
    <div class="table-head">
        <div class="table-title">Riwayat Order</div>
        <div class="table-count" id="tableCount">0 order</div>
    </div>
    <div id="tableBody">
        <div class="empty-state">⏳ Loading...</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let allOrders = [];

function fmt(n) { return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }

// Set default date range (7 hari)
const today = new Date();
const week  = new Date(today); week.setDate(week.getDate() - 7);
document.getElementById('dateFrom').value = week.toISOString().slice(0,10);
document.getElementById('dateTo').value   = today.toISOString().slice(0,10);

async function loadLaporan() {
    try {
        const from   = document.getElementById('dateFrom').value;
        const to     = document.getElementById('dateTo').value;
        const status = document.getElementById('filterStatus').value;
        const params = new URLSearchParams({ from, to, status });

        const res  = await fetch('/admin-api/laporan?' + params, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        allOrders  = data.orders || [];

        updateStats(allOrders);
        renderTable(allOrders);
        renderChart(data.chart || []);
    } catch(e) {
        document.getElementById('tableBody').innerHTML = '<div class="empty-state">❌ Gagal load data</div>';
    }
}

function updateStats(orders) {
    const done    = orders.filter(o => o.status === 'done');
    const total   = done.reduce((s,o) => s + parseInt(o.total_price), 0);
    const avg     = done.length ? Math.round(total / done.length) : 0;

    document.getElementById('sTotal').textContent = fmt(total);
    document.getElementById('sCount').textContent = orders.length;
    document.getElementById('sDone').textContent  = done.length;
    document.getElementById('sAvg').textContent   = fmt(avg);
}

function renderTable(orders) {
    document.getElementById('tableCount').textContent = orders.length + ' order';
    if (!orders.length) {
        document.getElementById('tableBody').innerHTML = '<div class="empty-state">📭 Tidak ada data</div>';
        return;
    }
    const statusLabel = { pending:'⏳ Pending', process:'🔄 Proses', done:'✅ Selesai', cancel:'❌ Batal' };
    const statusClass = { pending:'sb-pending', process:'sb-process', done:'sb-done', cancel:'sb-cancel' };
    const payLabel    = { qris:'QRIS', dana:'DANA', gopay:'GoPay', cash:'Cash' };

    document.getElementById('tableBody').innerHTML = `
        <table>
            <thead><tr>
                <th>#Order</th><th>Meja</th><th>Items</th>
                <th>Total</th><th>Bayar</th><th>Status</th><th>Waktu</th>
            </tr></thead>
            <tbody>
            ${orders.map(o => `
                <tr>
                    <td style="font-family:monospace;color:var(--muted);">#${String(o.id).padStart(4,'0')}</td>
                    <td>Meja ${o.table?.table_number||'-'}</td>
                    <td style="color:var(--muted);font-size:0.75rem;">${(o.order_items||[]).map(i=>(i.product?.name||'?')+'x'+i.quantity).join(', ')}</td>
                    <td style="font-weight:700;color:var(--accent);">${fmt(o.total_price)}</td>
                    <td>${payLabel[o.payment_method]||o.payment_method}</td>
                    <td><span class="sbadge ${statusClass[o.status]}">${statusLabel[o.status]||o.status}</span></td>
                    <td style="color:var(--muted);font-size:0.75rem;">${new Date(o.created_at).toLocaleString('id-ID',{day:'2-digit',month:'short',hour:'2-digit',minute:'2-digit'})}</td>
                </tr>`).join('')}
            </tbody>
        </table>`;
}

function renderChart(chartData) {
    if (!chartData.length) { document.getElementById('barChart').innerHTML = '<div style="text-align:center;width:100%;color:var(--muted);font-size:0.8rem;">Belum ada data</div>'; return; }
    const max = Math.max(...chartData.map(d => d.total), 1);
    document.getElementById('barChart').innerHTML = chartData.map(d => `
        <div class="bar-col">
            <div class="bar" style="height:${Math.max((d.total/max)*100, 4)}%" title="${fmt(d.total)}"></div>
            <div class="bar-label">${d.label}</div>
        </div>`).join('');
}

function exportExcel() {
    const from   = document.getElementById('dateFrom').value;
    const to     = document.getElementById('dateTo').value;
    const status = document.getElementById('filterStatus').value;
    window.location.href = `/admin-api/laporan/export-excel?from=${from}&to=${to}&status=${status}`;
}

function exportPDF() {
    const from   = document.getElementById('dateFrom').value;
    const to     = document.getElementById('dateTo').value;
    const status = document.getElementById('filterStatus').value;
    window.location.href = `/admin-api/laporan/export-pdf?from=${from}&to=${to}&status=${status}`;
}

loadLaporan();
</script>
@endpush