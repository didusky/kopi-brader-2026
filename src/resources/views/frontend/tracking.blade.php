@extends('layouts.app')
@section('title', 'Pesanan Saya - Kopi Brader')

@push('styles')
<style>
    body { padding-bottom: 80px; }

    .track-header {
        position: sticky; top: 0; z-index: 50;
        background: rgba(10,10,15,.96); backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border); padding: 14px 20px;
        display: flex; align-items: center; gap: 12px;
    }
    .back-btn {
        background: var(--surface); border: 1px solid var(--border);
        color: var(--text); width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none; font-size: 1.1rem; flex-shrink: 0;
    }
    .track-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem; }
    .live-indicator {
        margin-left: auto; display: flex; align-items: center; gap: 5px;
        font-size: 0.68rem; color: var(--green);
        font-family: 'JetBrains Mono', monospace;
    }
    .live-dot { width: 6px; height: 6px; background: var(--green); border-radius: 50%; animation: blink 1.5s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }

    /* SEARCH */
    .search-section { padding: 20px; }
    .search-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 18px; }
    .search-label { font-size: 0.75rem; color: var(--muted); margin-bottom: 8px; display: block; }
    .search-row { display: flex; gap: 10px; }
    .order-input {
        flex: 1; background: var(--surface2); border: 1px solid var(--border);
        border-radius: 10px; padding: 12px 16px; color: var(--text);
        font-family: 'JetBrains Mono', monospace; font-size: 1rem;
        outline: none; transition: border-color .2s; letter-spacing: 3px;
    }
    .order-input:focus { border-color: var(--accent); }
    .order-input::placeholder { font-family: 'Space Grotesk', sans-serif; letter-spacing: 0; font-size: 0.82rem; color: var(--muted); }
    .cek-btn {
        background: var(--accent); color: #000; font-weight: 700;
        border: none; padding: 12px 20px; border-radius: 10px; cursor: pointer;
        font-size: 0.88rem; white-space: nowrap; transition: all .2s;
    }
    .cek-btn:hover { transform: scale(1.03); }

    /* RESULT */
    .result-wrap { padding: 0 20px; }

    /* ORDER HERO */
    .order-hero {
        background: linear-gradient(135deg, rgba(212,255,0,.08), rgba(124,106,255,.05));
        border: 1px solid rgba(212,255,0,.2); border-radius: 18px;
        padding: 20px; margin-bottom: 14px; position: relative; overflow: hidden;
    }
    .order-hero::before {
        content: '☕'; position: absolute; right: -10px; bottom: -20px;
        font-size: 7rem; opacity: .06;
    }
    .order-hero-id { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--accent); margin-bottom: 8px; }
    .order-hero-total { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; color: var(--accent); }
    .order-hero-meta { font-size: 0.75rem; color: var(--muted); margin-top: 6px; }

    /* STATUS TRACKER */
    .tracker-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 18px; margin-bottom: 14px; }
    .tracker-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; margin-bottom: 18px; }

    .step-row { display: flex; gap: 14px; padding-bottom: 22px; position: relative; }
    .step-row:last-child { padding-bottom: 0; }
    .step-row:not(:last-child)::before {
        content: ''; position: absolute; left: 16px; top: 34px;
        width: 2px; height: calc(100% - 14px); background: var(--border);
    }
    .step-row.done::before { background: var(--accent); }
    .step-row.active::before { background: linear-gradient(to bottom, var(--accent), var(--border)); }

    .step-dot {
        width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; border: 2px solid var(--border);
        background: var(--surface2); z-index: 1; transition: all .3s;
    }
    .step-row.done .step-dot { background: var(--accent); border-color: var(--accent); }
    .step-row.active .step-dot {
        background: var(--accent); border-color: var(--accent);
        box-shadow: 0 0 0 6px rgba(212,255,0,.15);
        animation: pulseDot 1.8s infinite;
    }
    @keyframes pulseDot { 0%,100%{box-shadow:0 0 0 6px rgba(212,255,0,.15)} 50%{box-shadow:0 0 0 12px rgba(212,255,0,.04)} }

    .step-body { flex: 1; padding-top: 4px; }
    .step-name { font-weight: 700; font-size: 0.9rem; margin-bottom: 3px; }
    .step-row.done .step-name { color: var(--text); }
    .step-row.active .step-name { color: var(--accent); }
    .step-row:not(.done):not(.active) .step-name { color: var(--muted); }
    .step-desc { font-size: 0.73rem; color: var(--muted); line-height: 1.5; }
    .step-row.active .step-desc { color: rgba(212,255,0,.6); }

    /* ETA BADGE */
    .eta-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(212,255,0,.08); border: 1px solid rgba(212,255,0,.2);
        color: var(--accent); font-size: 0.7rem; font-weight: 700;
        padding: 4px 12px; border-radius: 100px; margin-top: 8px;
        font-family: 'JetBrains Mono', monospace;
    }

    /* ITEMS */
    .items-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 16px; margin-bottom: 14px; }
    .items-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; margin-bottom: 12px; }
    .item-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--border); }
    .item-row:last-child { border-bottom: none; padding-bottom: 0; }
    .item-thumb { width: 38px; height: 38px; border-radius: 8px; background: var(--surface2); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; overflow: hidden; }
    .item-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
    .item-info { flex: 1; }
    .item-name { font-weight: 600; font-size: 0.83rem; }
    .item-qty { font-size: 0.7rem; color: var(--muted); }
    .item-price { font-family: 'Syne', sans-serif; font-size: 0.88rem; font-weight: 700; color: var(--accent); }

    .total-bar { display: flex; justify-content: space-between; padding: 10px 0 0; border-top: 1px solid var(--border); margin-top: 4px; }
    .total-bar .lbl { font-family: 'Syne', sans-serif; font-weight: 800; }
    .total-bar .val { font-family: 'Syne', sans-serif; font-weight: 800; color: var(--accent); font-size: 1.1rem; }

    /* DONE STATE */
    .done-card {
        background: linear-gradient(135deg, rgba(34,197,94,.08), rgba(212,255,0,.04));
        border: 1px solid rgba(34,197,94,.25); border-radius: 16px;
        padding: 24px; text-align: center; margin-bottom: 14px;
    }
    .done-icon { font-size: 3.5rem; margin-bottom: 12px; animation: popIn .5s cubic-bezier(.34,1.56,.64,1); }
    @keyframes popIn { from{transform:scale(0)} to{transform:scale(1)} }
    .done-title { font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--green); margin-bottom: 6px; }
    .done-sub { font-size: 0.82rem; color: var(--muted); line-height: 1.6; }

    /* RATING */
    .rating-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 18px; margin-bottom: 14px; }
    .rating-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; margin-bottom: 14px; }
    .stars { display: flex; gap: 8px; justify-content: center; margin-bottom: 12px; }
    .star { font-size: 2rem; cursor: pointer; transition: transform .2s; filter: grayscale(1); }
    .star:hover, .star.active { filter: grayscale(0); transform: scale(1.2); }
    .review-input {
        width: 100%; background: var(--surface2); border: 1px solid var(--border);
        border-radius: 10px; padding: 10px 12px; color: var(--text);
        font-family: 'Space Grotesk', sans-serif; font-size: 0.83rem;
        resize: none; outline: none; min-height: 72px; transition: border-color .2s;
    }
    .review-input:focus { border-color: var(--accent); }
    .review-btn {
        width: 100%; background: var(--accent); color: #000; font-weight: 700;
        border: none; padding: 12px; border-radius: 10px; cursor: pointer;
        margin-top: 10px; font-size: 0.88rem; transition: all .2s;
    }

    /* RECENT */
    .recent-section { padding: 0 20px; }
    .recent-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; margin-bottom: 10px; }
    .recent-item {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 12px; padding: 12px 14px; margin-bottom: 8px;
        display: flex; align-items: center; justify-content: space-between;
        cursor: pointer; transition: border-color .2s;
    }
    .recent-item:hover { border-color: var(--accent); }
    .ri-id { font-family: 'JetBrains Mono', monospace; font-size: 0.78rem; color: var(--accent); }
    .ri-meta { font-size: 0.7rem; color: var(--muted); margin-top: 2px; }
    .sbadge { display: inline-flex; padding: 3px 9px; border-radius: 100px; font-size: 0.66rem; font-weight: 700; }
    .sb-pending { background: rgba(255,200,0,.15); color: #ffc800; }
    .sb-process { background: rgba(124,106,255,.15); color: #7c6aff; }
    .sb-done    { background: rgba(34,197,94,.15); color: #22c55e; }
    .sb-cancel  { background: rgba(239,68,68,.15); color: #ef4444; }

    /* REFRESH BTN */
    .refresh-bar {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        padding: 10px; margin: 0 20px 14px;
        background: var(--surface); border: 1px solid var(--border); border-radius: 10px;
        font-size: 0.75rem; color: var(--muted); cursor: pointer; transition: all .2s;
    }
    .refresh-bar:hover { border-color: var(--accent); color: var(--accent); }
</style>
@endpush

@section('content')

<div class="track-header">
    <a href="/s/{{ $table_number }}" class="back-btn">←</a>
    <div class="track-title">Pesanan Saya</div>
    <div class="live-indicator">
        <div class="live-dot"></div> LIVE
    </div>
</div>

<div class="search-section">
    <div class="search-card">
        <label class="search-label">📋 Nomor Order lo</label>
        <div class="search-row">
            <input class="order-input" id="orderInput" type="number" placeholder="Masukkan nomor order..." min="1">
            <button class="cek-btn" onclick="cekOrder()">Cek →</button>
        </div>
    </div>
</div>

<div class="result-wrap" id="resultWrap" style="display:none">
    <div id="resultContent"></div>
    <div class="refresh-bar" onclick="cekOrder()">🔄 Refresh status sekarang</div>
</div>

<div class="recent-section" id="recentSection">
    <div class="recent-title">🕐 Riwayat Cek</div>
    <div id="recentList">
        <div style="text-align:center;padding:20px;color:var(--muted);font-size:0.82rem;">Belum ada riwayat</div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const TABLE  = '{{ $table_number }}';
const EMOJI  = { coffee:'☕', noncoffee:'🧋', food:'🍞', snack:'🍿' };
const SL = { pending:'⏳ Menunggu', process:'🔄 Diproses', done:'✅ Selesai', cancel:'❌ Dibatal' };
const SC = { pending:'sb-pending', process:'sb-process', done:'sb-done', cancel:'sb-cancel' };

let autoTimer = null;
let currentId = null;
let selectedRating = 0;
let recent = JSON.parse(localStorage.getItem('kb_recent_' + TABLE) || '[]');

function fmt(n){ return 'Rp ' + parseInt(n).toLocaleString('id-ID'); }

// Auto-load dari URL param atau localStorage
window.addEventListener('load', () => {
    const p = new URLSearchParams(window.location.search);
    const oid = p.get('order') || localStorage.getItem('kb_last_order_' + TABLE);
    if (oid) { document.getElementById('orderInput').value = oid; cekOrder(); }
    renderRecent();
});

async function cekOrder() {
    const id = document.getElementById('orderInput').value.trim();
    if (!id) { alert('Masukkan nomor order dulu!'); return; }
    currentId = id;
    localStorage.setItem('kb_last_order_' + TABLE, id);

    try {
        const r = await fetch(`/status/order/${id}`, { headers: { Accept: 'application/json' } });
        const d = await r.json();
        if (!d.success) {
            document.getElementById('resultWrap').style.display = 'block';
            document.getElementById('resultContent').innerHTML = `
                <div style="text-align:center;padding:32px;background:var(--surface);border:1px solid var(--border);border-radius:16px;margin-bottom:14px;">
                    <div style="font-size:3rem;margin-bottom:10px">🔍</div>
                    <div style="font-weight:700;margin-bottom:4px">Order #${id} tidak ditemukan</div>
                    <div style="font-size:0.8rem;color:var(--muted)">Pastikan nomor order lo benar ya</div>
                </div>`;
            return;
        }
        saveRecent(d.order);
        renderResult(d.order);
        startAutoRefresh();
    } catch(e) { alert('Koneksi error, coba lagi.'); }
}

const STEPS = {
    pending: [
        { icon:'📝', name:'Order Diterima',    desc:'Pesanan lo udah masuk ke sistem kasir', state:'done'    },
        { icon:'👨‍🍳', name:'Sedang Diproses',   desc:'Barista lagi nyiapin pesanan lo...',   state:'active', eta:'~5-10 menit' },
        { icon:'🛎️', name:'Siap Disajikan',    desc:'Pesanan akan segera diantar ke meja',   state:'waiting' },
    ],
    process: [
        { icon:'📝', name:'Order Diterima',    desc:'Pesanan lo udah masuk ke sistem kasir', state:'done'   },
        { icon:'👨‍🍳', name:'Sedang Diproses',   desc:'Barista lagi nyiapin pesanan lo ☕',    state:'active', eta:'~3-7 menit' },
        { icon:'🛎️', name:'Siap Disajikan',    desc:'Pesanan akan segera diantar ke meja',   state:'waiting'},
    ],
    done: [
        { icon:'📝', name:'Order Diterima',    desc:'Pesanan masuk ke sistem',               state:'done' },
        { icon:'👨‍🍳', name:'Diproses',          desc:'Barista udah nyiapin pesanan lo',       state:'done' },
        { icon:'🛎️', name:'Selesai!',          desc:'Pesanan udah diantar, selamat menikmati!', state:'active' },
    ],
    cancel: [
        { icon:'📝', name:'Order Diterima',    desc:'Pesanan masuk ke sistem',               state:'done'   },
        { icon:'❌', name:'Dibatalkan',         desc:'Pesanan ini telah dibatalkan',          state:'active' },
    ],
};

function renderResult(order) {
    const oid   = String(order.id).padStart(4, '0');
    const steps = STEPS[order.status] || STEPS.pending;
    const isDone   = order.status === 'done';
    const isCancel = order.status === 'cancel';

    const stepsHtml = steps.map(s => `
        <div class="step-row ${s.state}">
            <div class="step-dot">${s.icon}</div>
            <div class="step-body">
                <div class="step-name">${s.name}</div>
                <div class="step-desc">${s.desc}</div>
                ${s.eta && s.state === 'active' ? `<div class="eta-badge">⏱ ETA ${s.eta}</div>` : ''}
            </div>
        </div>`).join('');

    const itemsHtml = (order.order_items || []).map(i => `
        <div class="item-row">
            <div class="item-thumb">
                ${i.product?.image
                    ? `<img src="/images/${i.product.image}" alt="${i.product?.name}">`
                    : (EMOJI[i.product?.category] || '☕')}
            </div>
            <div class="item-info">
                <div class="item-name">${i.product?.name || '?'}</div>
                <div class="item-qty">${i.quantity}x · ${fmt(i.price)}</div>
            </div>
            <div class="item-price">${fmt(i.subtotal)}</div>
        </div>`).join('');

    const payLabel = { qris:'QRIS', dana:'DANA', gopay:'GoPay', cash:'Cash' };

    document.getElementById('resultWrap').style.display = 'block';
    document.getElementById('resultContent').innerHTML = `
        ${isDone ? `
        <div class="done-card">
            <div class="done-icon">🎉</div>
            <div class="done-title">Pesanan Siap!</div>
            <div class="done-sub">Pesanan lo udah selesai dibuat.<br>Barista lagi mengantar ke meja lo sekarang~ ☕</div>
        </div>` : ''}

        <div class="order-hero">
            <div class="order-hero-id"># ORDER-${oid}</div>
            <div class="order-hero-total">${fmt(order.total_price)}</div>
            <div class="order-hero-meta">
                🍽️ Meja ${order.table?.table_number || '-'} &nbsp;·&nbsp;
                💳 ${payLabel[order.payment_method] || order.payment_method} &nbsp;·&nbsp;
                <span class="sbadge ${SC[order.status]}">${SL[order.status] || order.status}</span>
            </div>
        </div>

        <div class="tracker-card">
            <div class="tracker-title">📍 Tracking Pesanan</div>
            ${stepsHtml}
        </div>

        <div class="items-card">
            <div class="items-title">📋 Detail Pesanan</div>
            ${itemsHtml}
            ${order.notes ? `<div style="padding:8px 0;font-size:0.78rem;color:var(--muted);border-top:1px solid var(--border);margin-top:4px">📝 <strong style="color:var(--text)">Catatan:</strong> ${order.notes}</div>` : ''}
            <div class="total-bar">
                <span class="lbl">Total</span>
                <span class="val">${fmt(order.total_price)}</span>
            </div>
        </div>

        ${isDone ? `
        <div class="rating-card" id="ratingCard">
            <div class="rating-title">⭐ Gimana pesanan lo?</div>
            <div class="stars">
                ${[1,2,3,4,5].map(n => `<div class="star" onclick="setRating(${n})" data-val="${n}">⭐</div>`).join('')}
            </div>
            <textarea class="review-input" id="reviewText" placeholder="Cerita pengalaman lo (opsional)..."></textarea>
            <button class="review-btn" onclick="submitRating(${order.id})">Kirim Review ✨</button>
        </div>` : ''}`;
}

function setRating(n) {
    selectedRating = n;
    document.querySelectorAll('.star').forEach((s, i) => {
        s.classList.toggle('active', i < n);
        s.style.filter = i < n ? 'grayscale(0)' : 'grayscale(1)';
        s.style.transform = i < n ? 'scale(1.15)' : 'scale(1)';
    });
}

function submitRating(orderId) {
    if (!selectedRating) { alert('Pilih bintang dulu bro!'); return; }
    // Simpan ke localStorage (bisa dikirim ke backend juga)
    const reviews = JSON.parse(localStorage.getItem('kb_reviews') || '{}');
    reviews[orderId] = { rating: selectedRating, text: document.getElementById('reviewText').value, time: new Date().toISOString() };
    localStorage.setItem('kb_reviews', JSON.stringify(reviews));
    document.getElementById('ratingCard').innerHTML = `
        <div style="text-align:center;padding:16px">
            <div style="font-size:2.5rem;margin-bottom:8px">${'⭐'.repeat(selectedRating)}</div>
            <div style="font-weight:700;margin-bottom:4px">Makasih reviewnya! 🙏</div>
            <div style="font-size:0.78rem;color:var(--muted)">Feedback lo sangat berarti buat Kopi Brader</div>
        </div>`;
}

function saveRecent(order) {
    recent = recent.filter(r => r.id !== order.id);
    recent.unshift({ id: order.id, status: order.status, total: order.total_price, meja: order.table?.table_number });
    if (recent.length > 5) recent = recent.slice(0, 5);
    localStorage.setItem('kb_recent_' + TABLE, JSON.stringify(recent));
    renderRecent();
}

function renderRecent() {
    if (!recent.length) return;
    document.getElementById('recentList').innerHTML = recent.map(r => `
        <div class="recent-item" onclick="loadById(${r.id})">
            <div>
                <div class="ri-id"># ORDER-${String(r.id).padStart(4,'0')}</div>
                <div class="ri-meta">Meja ${r.meja || '-'} · ${fmt(r.total)}</div>
            </div>
            <span class="sbadge ${SC[r.status] || 'sb-pending'}">${SL[r.status] || r.status}</span>
        </div>`).join('');
}

function loadById(id) {
    document.getElementById('orderInput').value = id;
    cekOrder();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function startAutoRefresh() {
    if (autoTimer) clearInterval(autoTimer);
    autoTimer = setInterval(() => { if (currentId) cekOrder(); }, 12000);
}
</script>
@endpush