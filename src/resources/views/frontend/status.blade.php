@extends('layouts.app')
@section('title', 'Cek Status Order - Kopi Brader')

@push('styles')
<style>
    body { min-height: 100vh; display: flex; flex-direction: column; }

    .status-header {
        padding: 20px 20px 0;
        display: flex; align-items: center; gap: 12px;
    }
    .back-btn {
        background: var(--surface); border: 1px solid var(--border);
        color: var(--text); width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 1.1rem; flex-shrink: 0;
    }
    .header-title { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 800; }

    .hero { padding: 32px 20px 20px; text-align: center; }
    .hero-icon { font-size: 3.5rem; margin-bottom: 12px; }
    .hero-title { font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 800; margin-bottom: 6px; }
    .hero-sub { color: var(--muted); font-size: 0.85rem; line-height: 1.6; }

    .search-section { padding: 0 20px 20px; }
    .search-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; padding: 20px;
    }
    .search-label { font-size: 0.78rem; color: var(--muted); margin-bottom: 8px; display: block; }
    .search-row { display: flex; gap: 10px; }
    .order-input {
        flex: 1; background: var(--surface2); border: 1px solid var(--border);
        border-radius: 10px; padding: 12px 16px; color: var(--text);
        font-family: 'JetBrains Mono', monospace; font-size: 0.9rem; outline: none;
        transition: border-color 0.2s; letter-spacing: 2px;
    }
    .order-input:focus { border-color: var(--accent); }
    .order-input::placeholder { color: var(--muted); letter-spacing: 0; font-family: 'Space Grotesk', sans-serif; font-size: 0.82rem; }
    .cek-btn {
        background: var(--accent); color: #000; font-weight: 700;
        border: none; padding: 12px 20px; border-radius: 10px; cursor: pointer;
        font-family: 'Space Grotesk', sans-serif; font-size: 0.88rem; white-space: nowrap;
        transition: all 0.2s;
    }
    .cek-btn:hover { transform: scale(1.03); }
    .cek-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    /* RESULT */
    .result-section { padding: 0 20px; display: none; }
    .result-section.show { display: block; }

    .order-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden; margin-bottom: 14px;
    }
    .order-card-head {
        padding: 16px 20px;
        display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid var(--border);
    }
    .order-id { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--accent); }
    .order-meja { font-size: 0.75rem; color: var(--muted); margin-top: 2px; }

    /* STATUS STEPS */
    .status-steps { padding: 20px; }
    .step-row { display: flex; gap: 14px; align-items: flex-start; padding-bottom: 20px; position: relative; }
    .step-row:last-child { padding-bottom: 0; }
    .step-row:not(:last-child)::before {
        content: ''; position: absolute; left: 15px; top: 32px;
        width: 2px; height: calc(100% - 12px);
        background: var(--border);
    }
    .step-row.done::before { background: var(--accent); }
    .step-row.active::before { background: linear-gradient(to bottom, var(--accent), var(--border)); }

    .step-dot {
        width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; border: 2px solid var(--border);
        background: var(--surface2); z-index: 1;
    }
    .step-row.done .step-dot { background: var(--accent); border-color: var(--accent); }
    .step-row.active .step-dot {
        background: var(--accent); border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(212,255,0,.2);
        animation: pulseStep 1.5s infinite;
    }
    @keyframes pulseStep { 0%,100%{box-shadow:0 0 0 4px rgba(212,255,0,.2)} 50%{box-shadow:0 0 0 8px rgba(212,255,0,.05)} }

    .step-info { flex: 1; }
    .step-title { font-weight: 700; font-size: 0.88rem; margin-bottom: 2px; }
    .step-row.done .step-title { color: var(--text); }
    .step-row.active .step-title { color: var(--accent); }
    .step-row:not(.done):not(.active) .step-title { color: var(--muted); }
    .step-desc { font-size: 0.72rem; color: var(--muted); line-height: 1.5; }
    .step-time { font-family: 'JetBrains Mono', monospace; font-size: 0.65rem; color: var(--muted); margin-top: 3px; }

    .order-items-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; padding: 16px; margin-bottom: 14px;
    }
    .items-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; margin-bottom: 12px; }
    .item-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; border-bottom: 1px solid var(--border); font-size: 0.83rem; }
    .item-row:last-child { border-bottom: none; }
    .item-row-name { color: var(--text); }
    .item-row-qty { color: var(--muted); font-size: 0.72rem; }
    .item-row-price { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--accent); }

    .total-row { display: flex; justify-content: space-between; padding: 10px 0 0; border-top: 1px solid var(--border); margin-top: 4px; }
    .total-row .lbl { font-family: 'Syne', sans-serif; font-weight: 800; }
    .total-row .val { font-family: 'Syne', sans-serif; font-weight: 800; color: var(--accent); font-size: 1.1rem; }

    .refresh-status-btn {
        width: 100%; background: var(--surface); border: 1px solid var(--border);
        color: var(--text); padding: 12px; border-radius: 12px; cursor: pointer;
        font-size: 0.85rem; margin-bottom: 20px; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .refresh-status-btn:hover { border-color: var(--accent); color: var(--accent); }

    /* RECENT ORDERS */
    .recent-section { padding: 0 20px 100px; }
    .recent-title { font-family: 'Syne', sans-serif; font-size: 0.95rem; font-weight: 800; margin-bottom: 12px; }
    .recent-item {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 12px; padding: 12px 14px; margin-bottom: 8px;
        display: flex; align-items: center; justify-content: space-between; cursor: pointer;
        transition: border-color 0.2s;
    }
    .recent-item:hover { border-color: var(--accent); }
    .ri-id { font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; color: var(--accent); }
    .ri-info { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }

    /* STATUS BADGE INLINE */
    .sbadge { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 100px; font-size: 0.68rem; font-weight: 700; }
    .sb-pending  { background: rgba(255,200,0,.15); color: #ffc800; }
    .sb-process  { background: rgba(124,106,255,.15); color: #7c6aff; }
    .sb-done     { background: rgba(34,197,94,.15); color: #22c55e; }
    .sb-cancel   { background: rgba(239,68,68,.15); color: #ef4444; }

    .not-found {
        text-align: center; padding: 32px 20px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; margin-bottom: 14px;
    }
    .not-found-icon { font-size: 2.5rem; margin-bottom: 8px; }
    .not-found-text { font-size: 0.85rem; color: var(--muted); }

    /* AUTO REFRESH INDICATOR */
    .auto-refresh {
        display: flex; align-items: center; gap: 6px;
        font-size: 0.68rem; color: var(--muted); padding: 8px 20px;
        justify-content: center;
    }
    .ar-dot { width: 6px; height: 6px; background: #22c55e; border-radius: 50%; animation: pulse 2s infinite; }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
</style>
@endpush

@section('content')

<div class="status-header">
    <a href="javascript:history.back()" class="back-btn">←</a>
    <div class="header-title">Cek Status Order</div>
</div>

<div class="hero">
    <div class="hero-icon">🔍</div>
    <div class="hero-title">Status Pesanan</div>
    <div class="hero-sub">Masukkan nomor order lo untuk<br>cek status pesanan real-time</div>
</div>

<div class="search-section">
    <div class="search-card">
        <label class="search-label">Nomor Order</label>
        <div class="search-row">
            <input class="order-input" id="orderInput" type="number" placeholder="Contoh: 3" min="1">
            <button class="cek-btn" onclick="cekStatus()">🔍 Cek</button>
        </div>
    </div>
</div>

<div class="result-section" id="resultSection">
    <div id="resultContent"></div>
    <button class="refresh-status-btn" onclick="cekStatus()">
        🔄 Refresh Status
    </button>
</div>

<div class="auto-refresh" id="autoRefreshInfo" style="display:none">
    <div class="ar-dot"></div>
    Auto refresh setiap 10 detik
</div>

<div class="recent-section" id="recentSection">
    <div class="recent-title">📋 Order Terakhir</div>
    <div id="recentList">
        <div style="text-align:center;padding:20px;color:var(--muted);font-size:0.82rem;">Belum ada order yang dicek</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const STEP_CONFIG = {
    pending: {
        steps: [
            { key: 'pending',  icon: '📝', title: 'Order Diterima',     desc: 'Pesanan lo sudah masuk ke sistem',           state: 'done'    },
            { key: 'process',  icon: '👨‍🍳', title: 'Sedang Diproses',   desc: 'Barista sedang menyiapkan pesanan lo',        state: 'waiting' },
            { key: 'done',     icon: '✅', title: 'Siap Disajikan',     desc: 'Pesanan siap dan akan segera diantar',        state: 'waiting' },
        ]
    },
    process: {
        steps: [
            { key: 'pending',  icon: '📝', title: 'Order Diterima',     desc: 'Pesanan lo sudah masuk ke sistem',            state: 'done'    },
            { key: 'process',  icon: '👨‍🍳', title: 'Sedang Diproses',   desc: 'Barista sedang menyiapkan pesanan lo ☕',      state: 'active'  },
            { key: 'done',     icon: '✅', title: 'Siap Disajikan',     desc: 'Pesanan siap dan akan segera diantar',        state: 'waiting' },
        ]
    },
    done: {
        steps: [
            { key: 'pending',  icon: '📝', title: 'Order Diterima',     desc: 'Pesanan lo sudah masuk ke sistem',            state: 'done'    },
            { key: 'process',  icon: '👨‍🍳', title: 'Diproses',          desc: 'Barista sudah menyiapkan pesanan lo',         state: 'done'    },
            { key: 'done',     icon: '✅', title: 'Selesai!',           desc: 'Pesanan sudah siap, selamat menikmati! ☕',   state: 'active'  },
        ]
    },
    cancel: {
        steps: [
            { key: 'pending',  icon: '📝', title: 'Order Diterima',     desc: 'Pesanan masuk ke sistem',                     state: 'done'    },
            { key: 'cancel',   icon: '❌', title: 'Dibatalkan',         desc: 'Pesanan ini telah dibatalkan',                state: 'active'  },
        ]
    },
};

let currentOrderId = null;
let autoRefreshTimer = null;
let recentOrders = JSON.parse(localStorage.getItem('recentOrders') || '[]');

function fmt(n) { return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }

function saveRecent(order) {
    recentOrders = recentOrders.filter(r => r.id !== order.id);
    recentOrders.unshift({ id: order.id, status: order.status, total: order.total_price, meja: order.table?.table_number });
    if (recentOrders.length > 5) recentOrders = recentOrders.slice(0, 5);
    localStorage.setItem('recentOrders', JSON.stringify(recentOrders));
    renderRecent();
}

function renderRecent() {
    if (!recentOrders.length) return;
    const statusLabel = { pending:'⏳ Pending', process:'🔄 Diproses', done:'✅ Selesai', cancel:'❌ Batal' };
    const statusClass = { pending:'sb-pending', process:'sb-process', done:'sb-done', cancel:'sb-cancel' };
    document.getElementById('recentList').innerHTML = recentOrders.map(r => `
        <div class="recent-item" onclick="cekById(${r.id})">
            <div>
                <div class="ri-id"># ORDER-${String(r.id).padStart(4,'0')}</div>
                <div class="ri-info">Meja ${r.meja||'-'} · ${fmt(r.total)}</div>
            </div>
            <span class="sbadge ${statusClass[r.status]||'sb-pending'}">${statusLabel[r.status]||r.status}</span>
        </div>`).join('');
}

async function cekById(id) {
    document.getElementById('orderInput').value = id;
    await cekStatus();
}

async function cekStatus() {
    const raw = document.getElementById('orderInput').value.trim();
    if (!raw) { alert('Masukkan nomor order dulu!'); return; }

    const btn = document.querySelector('.cek-btn');
    btn.disabled = true; btn.textContent = '⏳';

    try {
        const res = await fetch(`/status/order/${raw}`, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        btn.disabled = false; btn.textContent = '🔍 Cek';

        if (!data.success) {
            showNotFound(raw);
            return;
        }

        currentOrderId = data.order.id;
        renderResult(data.order);
        saveRecent(data.order);
        startAutoRefresh();
    } catch(e) {
        btn.disabled = false; btn.textContent = '🔍 Cek';
        alert('Koneksi error, coba lagi.');
    }
}

function showNotFound(id) {
    document.getElementById('resultSection').classList.add('show');
    document.getElementById('resultContent').innerHTML = `
        <div class="not-found">
            <div class="not-found-icon">🔍</div>
            <div style="font-weight:700;margin-bottom:4px;">Order tidak ditemukan</div>
            <div class="not-found-text">Nomor order <strong>#${id}</strong> tidak ada.<br>Cek lagi nomor ordernya ya.</div>
        </div>`;
    document.getElementById('autoRefreshInfo').style.display = 'none';
}

function renderResult(order) {
    const oid = String(order.id).padStart(4, '0');
    const cfg = STEP_CONFIG[order.status] || STEP_CONFIG.pending;
    const stepsHtml = cfg.steps.map(s => `
        <div class="step-row ${s.state}">
            <div class="step-dot">${s.icon}</div>
            <div class="step-info">
                <div class="step-title">${s.title}</div>
                <div class="step-desc">${s.desc}</div>
            </div>
        </div>`).join('');

    const itemsHtml = (order.order_items||[]).map(i => `
        <div class="item-row">
            <div>
                <div class="item-row-name">${i.product?.name||'?'}</div>
                <div class="item-row-qty">${i.quantity}x · ${fmt(i.price)}</div>
            </div>
            <div class="item-row-price">${fmt(i.subtotal)}</div>
        </div>`).join('');

    const statusLabel = { pending:'⏳ Menunggu', process:'🔄 Diproses', done:'✅ Selesai', cancel:'❌ Dibatal' };
    const statusClass = { pending:'sb-pending', process:'sb-process', done:'sb-done', cancel:'sb-cancel' };
    const payLabel = { qris:'QRIS', dana:'DANA', gopay:'GoPay', cash:'Cash' };

    document.getElementById('resultSection').classList.add('show');
    document.getElementById('autoRefreshInfo').style.display = 'flex';
    document.getElementById('resultContent').innerHTML = `
        <div class="order-card">
            <div class="order-card-head">
                <div>
                    <div class="order-id"># ORDER-${oid}</div>
                    <div class="order-meja">🍽️ Meja ${order.table?.table_number||'-'} · ${payLabel[order.payment_method]||order.payment_method}</div>
                </div>
                <span class="sbadge ${statusClass[order.status]||'sb-pending'}">${statusLabel[order.status]||order.status}</span>
            </div>
            <div class="status-steps">${stepsHtml}</div>
        </div>
        <div class="order-items-card">
            <div class="items-title">📋 Detail Pesanan</div>
            ${itemsHtml}
            <div class="total-row">
                <span class="lbl">Total</span>
                <span class="val">${fmt(order.total_price)}</span>
            </div>
        </div>
        ${order.notes ? `<div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:12px 14px;margin-bottom:14px;font-size:0.82rem;color:var(--muted);">📝 <strong style="color:var(--text);">Catatan:</strong> ${order.notes}</div>` : ''}`;
}

function startAutoRefresh() {
    if (autoRefreshTimer) clearInterval(autoRefreshTimer);
    autoRefreshTimer = setInterval(async () => {
        if (!currentOrderId) return;
        try {
            const res = await fetch(`/status/order/${currentOrderId}`, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (data.success) {
                renderResult(data.order);
                saveRecent(data.order);
            }
        } catch(e) {}
    }, 10000);
}

// Init
renderRecent();

// Check URL param
const urlParams = new URLSearchParams(window.location.search);
const orderId = urlParams.get('order');
if (orderId) {
    document.getElementById('orderInput').value = orderId;
    cekStatus();
}
</script>
@endpush