@extends('layouts.app')
@section('title', 'Menu - Meja ' . $table->table_number)

@push('styles')
<style>
    body { padding-bottom: 100px; }

    /* HEADER */
    .page-header {
        position: sticky; top: 0; z-index: 50;
        backdrop-filter: blur(20px); background: rgba(10,10,15,0.95);
        border-bottom: 1px solid var(--border); padding: 14px 20px;
    }
    .header-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .header-brand { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 800; }
    .table-badge { background: var(--accent); color: #000; font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 100px; }
    .cat-tabs { display: flex; gap: 8px; overflow-x: auto; scrollbar-width: none; }
    .cat-tabs::-webkit-scrollbar { display: none; }
    .cat-tab { background: var(--surface2); border: 1px solid var(--border); color: var(--muted); font-size: 0.78rem; font-weight: 600; padding: 6px 14px; border-radius: 100px; cursor: pointer; white-space: nowrap; transition: all 0.2s; flex-shrink: 0; }
    .cat-tab.active { background: var(--accent); border-color: var(--accent); color: #000; }

    .search-wrap { padding: 14px 20px 0; }
    .search-input { width: 100%; background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 11px 16px 11px 42px; color: var(--text); font-family: 'Space Grotesk', sans-serif; font-size: 0.88rem; outline: none; transition: border-color 0.2s; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b6b7e' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: 13px center; }
    .search-input:focus { border-color: var(--accent); }
    .search-input::placeholder { color: var(--muted); }

    .promo-banner { margin: 14px 20px; background: linear-gradient(135deg, rgba(124,106,255,0.12), rgba(212,255,0,0.04)); border: 1px solid rgba(124,106,255,0.25); border-radius: 14px; padding: 12px 16px; display: flex; align-items: center; gap: 12px; }
    .promo-icon { font-size: 1.6rem; }
    .promo-title { font-weight: 700; font-size: 0.85rem; }
    .promo-sub { font-size: 0.72rem; color: var(--muted); }
    .promo-badge { background: var(--accent3); color: white; font-size: 0.65rem; font-weight: 700; padding: 3px 8px; border-radius: 6px; }

    /* MENU */
    .menu-section { padding: 0 20px; }
    .section-head { display: flex; align-items: center; justify-content: space-between; margin: 20px 0 12px; }
    .section-title { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 800; }
    .section-count { font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: var(--muted); }
    .menu-list { display: flex; flex-direction: column; gap: 10px; }
    .menu-item { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 12px; display: flex; gap: 12px; transition: all 0.2s; }
    .menu-item.sold-out { opacity: 0.5; pointer-events: none; }
    .item-thumb { width: 72px; height: 72px; border-radius: 10px; flex-shrink: 0; background: var(--surface2); display: flex; align-items: center; justify-content: center; font-size: 2rem; overflow: hidden; }
    .item-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
    .item-info { flex: 1; min-width: 0; }
    .item-name { font-weight: 700; font-size: 0.92rem; margin-bottom: 2px; }
    .item-desc { font-size: 0.73rem; color: var(--muted); line-height: 1.4; margin-bottom: 8px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .item-bottom { display: flex; align-items: center; justify-content: space-between; }
    .item-price { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 800; color: var(--accent); }
    .qty-control { display: flex; align-items: center; background: var(--surface2); border: 1px solid var(--border); border-radius: 9px; overflow: hidden; }
    .qty-btn { background: transparent; border: none; color: var(--text); width: 30px; height: 30px; font-size: 1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .qty-num { font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; font-weight: 700; min-width: 26px; text-align: center; }
    .add-btn { background: var(--accent); border: none; color: #000; font-weight: 700; font-size: 0.78rem; padding: 7px 14px; border-radius: 9px; cursor: pointer; font-family: 'Space Grotesk', sans-serif; }

    /* DIVIDER */
    .divider { margin: 28px 20px; border: none; border-top: 1px solid var(--border); position: relative; }
    .divider::after { content: '🛒 Pesanan Lo'; position: absolute; top: -10px; left: 50%; transform: translateX(-50%); background: var(--bg); padding: 0 12px; font-size: 0.75rem; color: var(--muted); font-family: 'JetBrains Mono', monospace; white-space: nowrap; }

    /* CHECKOUT */
    .checkout-section { padding: 0 20px; }
    .cart-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 16px; margin-bottom: 12px; }
    .cart-title { font-family: 'Syne', sans-serif; font-size: 0.95rem; font-weight: 800; margin-bottom: 12px; }
    .cart-empty { text-align: center; padding: 20px; color: var(--muted); font-size: 0.82rem; }
    .cart-item { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--border); }
    .cart-item:last-child { border-bottom: none; }
    .ci-emoji { width: 36px; height: 36px; background: var(--surface2); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    .ci-info { flex: 1; }
    .ci-name { font-weight: 600; font-size: 0.85rem; }
    .ci-sub { font-size: 0.7rem; color: var(--muted); }
    .ci-price { font-family: 'Syne', sans-serif; font-size: 0.9rem; font-weight: 800; color: var(--accent); white-space: nowrap; }

    .pay-options { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .pay-option { background: var(--surface2); border: 2px solid var(--border); border-radius: 12px; padding: 10px 12px; cursor: pointer; transition: all 0.2s; position: relative; }
    .pay-option.selected { border-color: var(--accent); background: rgba(212,255,0,.05); }
    .pay-icon { font-size: 1.4rem; margin-bottom: 2px; }
    .pay-name { font-weight: 700; font-size: 0.82rem; }
    .pay-sub { font-size: 0.65rem; color: var(--muted); }
    .pay-check { display: none; position: absolute; top: 6px; right: 6px; width: 14px; height: 14px; background: var(--accent); border-radius: 50%; align-items: center; justify-content: center; font-size: 0.5rem; color: #000; font-weight: 900; }
    .pay-option.selected .pay-check { display: flex; }

    .notes-input { width: 100%; background: var(--surface2); border: 1px solid var(--border); border-radius: 10px; padding: 10px 12px; color: var(--text); font-family: 'Space Grotesk', sans-serif; font-size: 0.85rem; resize: none; outline: none; transition: border-color 0.2s; min-height: 64px; }
    .notes-input:focus { border-color: var(--accent); }
    .notes-input::placeholder { color: var(--muted); }

    .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 0.85rem; }
    .summary-row .lbl { color: var(--muted); }
    .summary-row .val { font-family: 'JetBrains Mono', monospace; }
    .summary-row.total { border-top: 1px solid var(--border); margin-top: 6px; padding-top: 12px; }
    .summary-row.total .lbl { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.95rem; color: var(--text); }
    .summary-row.total .val { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.2rem; color: var(--accent); }

    /* ORDER BTN */
    .order-wrap { position: fixed; bottom: 0; left: 0; right: 0; padding: 12px 20px; background: linear-gradient(to top, var(--bg) 70%, transparent); z-index: 99; }
    .order-btn { width: 100%; display: flex; align-items: center; justify-content: space-between; background: var(--accent); color: #000; font-weight: 800; font-size: 0.95rem; padding: 16px 22px; border-radius: 14px; border: none; cursor: pointer; transition: all 0.2s; font-family: 'Syne', sans-serif; }
    .order-btn:disabled { opacity: .5; cursor: not-allowed; }
    .btn-price { background: rgba(0,0,0,.18); padding: 4px 12px; border-radius: 100px; font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; }

    /* PAYMENT OVERLAY */
    .payment-overlay { position: fixed; inset: 0; background: var(--bg); display: none; flex-direction: column; z-index: 200; overflow-y: auto; }
    .payment-overlay.open { display: flex; }
    .pay-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; background: var(--surface); position: sticky; top: 0; z-index: 10; }
    .pay-back-btn { background: var(--surface2); border: 1px solid var(--border); color: var(--text); width: 36px; height: 36px; border-radius: 10px; cursor: pointer; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; }
    .pay-header-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem; }
    .pay-body { padding: 20px; flex: 1; padding-bottom: 100px; }

    .order-info-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 16px; margin-bottom: 14px; }
    .order-num-badge { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--accent); background: rgba(212,255,0,.08); border: 1px solid rgba(212,255,0,.2); padding: 4px 12px; border-radius: 100px; display: inline-block; margin-bottom: 10px; }
    .order-total-big { font-family: 'Syne', sans-serif; font-size: 2.2rem; font-weight: 800; color: var(--accent); }
    .order-total-sub { font-size: 0.78rem; color: var(--muted); margin-top: 4px; }

    .pay-method-card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 16px; margin-bottom: 14px; }
    .pay-method-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.9rem; margin-bottom: 14px; }

    /* QRIS Box */
    .qris-box { background: white; border-radius: 16px; padding: 20px; display: flex; flex-direction: column; align-items: center; gap: 10px; }
    .qris-img { width: 200px; height: 200px; object-fit: contain; border-radius: 8px; }
    .qris-placeholder { width: 200px; height: 200px; border: 3px dashed #ccc; border-radius: 12px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; }
    .qris-placeholder-icon { font-size: 3.5rem; }
    .qris-placeholder-text { font-size: 0.72rem; color: #888; font-weight: 700; text-align: center; }
    .qris-label { color: #000; font-weight: 700; font-size: 0.9rem; }
    .qris-total { color: #e63946; font-size: 1.1rem; font-weight: 800; }
    .qris-note { color: #555; font-size: 0.72rem; text-align: center; }

    /* Instruksi box */
    .instruksi-box { background: var(--surface2); border-radius: 12px; padding: 16px; }
    .instruksi-icon { font-size: 2.5rem; text-align: center; margin-bottom: 6px; }
    .instruksi-title { font-weight: 700; font-size: 0.88rem; text-align: center; margin-bottom: 10px; }
    .instruksi-nomor { text-align: center; font-size: 1.4rem; font-weight: 800; color: var(--accent); margin-bottom: 4px; }
    .instruksi-atas-nama { text-align: center; font-size: 0.78rem; color: var(--muted); margin-bottom: 12px; }
    .instruksi-step { display: flex; gap: 10px; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid var(--border); }
    .instruksi-step:last-child { border-bottom: none; padding-bottom: 0; }
    .step-num { width: 22px; height: 22px; background: var(--accent); color: #000; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 900; flex-shrink: 0; margin-top: 2px; }
    .step-text { font-size: 0.82rem; color: var(--muted); line-height: 1.5; }

    .confirm-pay-btn { width: 100%; background: #22c55e; color: white; font-weight: 800; border: none; padding: 16px; border-radius: 14px; cursor: pointer; font-family: 'Syne', sans-serif; font-size: 0.95rem; transition: all 0.2s; }
    .confirm-pay-btn:hover { background: #16a34a; }

    /* SUCCESS OVERLAY */
    .success-overlay { position: fixed; inset: 0; background: rgba(10,10,15,.97); display: none; flex-direction: column; align-items: center; justify-content: center; z-index: 999; text-align: center; padding: 40px; }
    .success-icon { font-size: 5rem; margin-bottom: 20px; animation: popIn .5s cubic-bezier(.34,1.56,.64,1) forwards; }
    @keyframes popIn { from{transform:scale(0);opacity:0} to{transform:scale(1);opacity:1} }
    .success-title { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; margin-bottom: 8px; }
    .success-sub { color: var(--muted); font-size: 0.9rem; line-height: 1.7; margin-bottom: 24px; }
    .success-badge { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--accent); background: rgba(212,255,0,.08); border: 1px solid rgba(212,255,0,.2); padding: 6px 16px; border-radius: 100px; margin-bottom: 24px; }
    .back-btn { background: var(--accent); color: #000; font-weight: 700; padding: 12px 28px; border-radius: 100px; border: none; cursor: pointer; font-size: 0.9rem; }
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="page-header">
    <div class="header-top">
        <div class="header-brand">☕ Kopi Brader</div>
        <div class="table-badge">MEJA {{ $table->table_number }}</div>
    </div>
    <div class="cat-tabs">
        <button class="cat-tab active" onclick="filterCat(this,'all')">🍽️ Semua</button>
        <button class="cat-tab" onclick="filterCat(this,'coffee')">☕ Kopi</button>
        <button class="cat-tab" onclick="filterCat(this,'noncoffee')">🧋 Non-Kopi</button>
        <button class="cat-tab" onclick="filterCat(this,'food')">🍞 Makanan</button>
        <button class="cat-tab" onclick="filterCat(this,'snack')">🍿 Snack</button>
    </div>
</div>

<div class="search-wrap">
    <input class="search-input" type="text" placeholder="Cari menu..." oninput="searchMenu(this.value)">
</div>

<div class="promo-banner">
    <div class="promo-icon">⚡</div>
    <div class="promo-text">
        <div class="promo-title">Happy Hour 14.00–16.00</div>
        <div class="promo-sub">Semua minuman kopi diskon 15%!</div>
    </div>
    <div class="promo-badge">-15%</div>
</div>

<div class="menu-section">
    <div class="section-head">
        <div class="section-title">Menu</div>
        <div class="section-count" id="menuCount">{{ $products->count() }} items</div>
    </div>
    <div class="menu-list" id="menuList">
        @forelse($products as $product)
        <div class="menu-item {{ !$product->is_ready ? 'sold-out' : '' }}"
                data-cat="{{ $product->category }}"
                data-name="{{ strtolower($product->name) }}">
            <div class="item-thumb">
    @if($product->image)
        <img src="/images/{{ $product->image }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
    @else
        @switch($product->category)
            @case('coffee') ☕ @break
            @case('noncoffee') 🧋 @break
            @case('food') 🍞 @break
            @case('snack') 🍿 @break
            @default ☕
        @endswitch
    @endif
</div>s
            <div class="item-info">
                <div class="item-name">{{ $product->name }}</div>
                <div class="item-desc">{{ $product->description }}</div>
                <div class="item-bottom">
                    <span class="item-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @if($product->is_ready)
                    <div id="ctrl-{{ $product->id }}">
                        <button class="add-btn"
                            onclick="addItem({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->category }}')">
                            + Tambah
                        </button>
                    </div>
                    @else
                    <button class="add-btn" disabled style="opacity:.4;background:var(--surface2);color:var(--muted);">Habis</button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:40px;color:var(--muted);">
            <div style="font-size:2.5rem;margin-bottom:8px">😔</div>
            Menu belum tersedia
        </div>
        @endforelse
    </div>
</div>

<hr class="divider">

<div class="checkout-section">
    <div class="cart-card">
        <div class="cart-title">📋 Pesanan Lo</div>
        <div id="cartContainer">
            <div class="cart-empty">Belum ada item — tambah menu dulu di atas 👆</div>
        </div>
    </div>
    <div class="cart-card">
        <div class="cart-title">📝 Catatan</div>
        <textarea class="notes-input" id="notesInput" placeholder="Contoh: kurang manis, extra oat milk..."></textarea>
    </div>
    <div class="cart-card">
        <div class="cart-title">💳 Metode Pembayaran</div>
        <div class="pay-options">
            <div class="pay-option selected" onclick="selectPay(this,'qris')">
                <div class="pay-check">✓</div>
                <div class="pay-icon">🔲</div>
                <div class="pay-name">QRIS</div>
                <div class="pay-sub">Semua e-wallet</div>
            </div>
            <div class="pay-option" onclick="selectPay(this,'dana')">
                <div class="pay-check">✓</div>
                <div class="pay-icon">💙</div>
                <div class="pay-name">DANA</div>
                <div class="pay-sub">Transfer instan</div>
            </div>
            <div class="pay-option" onclick="selectPay(this,'gopay')">
                <div class="pay-check">✓</div>
                <div class="pay-icon">💚</div>
                <div class="pay-name">GoPay</div>
                <div class="pay-sub">Via QR GoPay</div>
            </div>
            <div class="pay-option" onclick="selectPay(this,'cash')">
                <div class="pay-check">✓</div>
                <div class="pay-icon">💵</div>
                <div class="pay-name">Cash</div>
                <div class="pay-sub">Bayar di kasir</div>
            </div>
        </div>
    </div>
    <div class="cart-card">
        <div class="cart-title">🧾 Ringkasan</div>
        <div class="summary-row">
            <span class="lbl">Subtotal (<span id="itemCount">0</span> item)</span>
            <span class="val" id="subtotalVal">Rp 0</span>
        </div>
        <div class="summary-row">
            <span class="lbl">Biaya Layanan</span>
            <span class="val">Rp 2.000</span>
        </div>
        <div class="summary-row total">
            <span class="lbl">Total</span>
            <span class="val" id="grandTotalVal">Rp 2.000</span>
        </div>
    </div>
</div>

<div class="order-wrap">
    <button class="order-btn" id="orderBtn" onclick="goToPayment()">
        <span>🛒 Lanjut Pembayaran</span>
        <span class="btn-price" id="btnTotal">Rp 2.000</span>
    </button>
</div>

{{-- ===== PAYMENT OVERLAY ===== --}}
<div class="payment-overlay" id="paymentOverlay">
    <div class="pay-header">
        <button class="pay-back-btn" onclick="closePayment()">←</button>
        <div class="pay-header-title">Konfirmasi Pembayaran</div>
    </div>
    <div class="pay-body">
        <div class="order-info-card">
            <div class="order-num-badge" id="payOrderNum"># ORDER-0000</div>
            <div class="order-total-big" id="payTotal">Rp 0</div>
            <div class="order-total-sub">Total termasuk biaya layanan · Meja <strong>{{ $table->table_number }}</strong></div>
        </div>
        <div class="pay-method-card" id="payMethodContent"></div>
        <button class="confirm-pay-btn" onclick="confirmPayment()">✅ Sudah Bayar — Konfirmasi</button>
    </div>
</div>

{{-- ===== SUCCESS OVERLAY ===== --}}
<div class="success-overlay" id="successOverlay">
    <div class="success-icon">🎉</div>
    <div class="success-title">Order Masuk!</div>
    <div class="success-sub">Pesanan lo udah diterima.<br>Barista lagi nyiapin sekarang~</div>
    <div class="success-badge" id="successBadge"># ORDER-0000</div>
    <div style="display:flex;flex-direction:column;gap:10px;width:100%;max-width:280px;margin-top:8px;">
        <button class="back-btn" onclick="cekStatusOrder()"
            style="background:var(--surface);border:2px solid var(--accent);color:var(--accent);font-size:0.95rem;">
            🔍 Lacak Pesanan Saya
        </button>
        <button class="back-btn" onclick="resetPage()"
            style="background:transparent;border:1px solid var(--border);color:var(--muted);font-size:0.85rem;">
            + Order Lagi
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
const TABLE  = '{{ $table->table_number }}';
const CSRF   = '{{ csrf_token() }}';
const FEE    = 2000;
const EMOJI  = { coffee:'☕', noncoffee:'🧋', food:'🍞', snack:'🍿' };

// ⚠️ GANTI info kafe lo di sini
const KAFE = {
    nama:     'Kopi Brader',
    qris_url: '/images/dana qr.jpeg', // Isi URL gambar QRIS lo, contoh: '/images/qris-kopi-brader.png'
    dana:     '08123456789',
    gopay:    '08123456789',
};

let cart = {}, selectedPay = 'qris', orderId = null, grandTotal = 0;

function addItem(id, name, price, cat) {
    if (!cart[id]) cart[id] = { name, price, cat, qty: 0 };
    cart[id].qty++;
    document.getElementById('ctrl-'+id).innerHTML = `
        <div class="qty-control">
            <button class="qty-btn" onclick="removeItem(${id})">−</button>
            <span class="qty-num" id="qty-${id}">${cart[id].qty}</span>
            <button class="qty-btn" onclick="addMore(${id})">+</button>
        </div>`;
    updateUI();
}

function addMore(id) {
    cart[id].qty++;
    document.getElementById('qty-'+id).textContent = cart[id].qty;
    updateUI();
}

function removeItem(id) {
    cart[id].qty--;
    if (cart[id].qty <= 0) {
        const { name, price, cat } = cart[id];
        delete cart[id];
        document.getElementById('ctrl-'+id).innerHTML =
            `<button class="add-btn" onclick="addItem(${id},'${name.replace(/'/g,"\\'")}',${price},'${cat}')">+ Tambah</button>`;
    } else {
        document.getElementById('qty-'+id).textContent = cart[id].qty;
    }
    updateUI();
}

function updateUI() {
    const items = Object.entries(cart);
    const sub   = items.reduce((s,[,i]) => s + i.price * i.qty, 0);
    const count = items.reduce((s,[,i]) => s + i.qty, 0);
    grandTotal  = sub + FEE;

    document.getElementById('itemCount').textContent     = count;
    document.getElementById('subtotalVal').textContent   = fmt(sub);
    document.getElementById('grandTotalVal').textContent = fmt(grandTotal);
    document.getElementById('btnTotal').textContent      = fmt(grandTotal);

    document.getElementById('cartContainer').innerHTML = items.length === 0
        ? '<div class="cart-empty">Belum ada item — tambah menu dulu di atas 👆</div>'
        : items.map(([id,i]) => `
            <div class="cart-item">
                <div class="ci-emoji">${EMOJI[i.cat]||'☕'}</div>
                <div class="ci-info">
                    <div class="ci-name">${i.name}</div>
                    <div class="ci-sub">${i.qty}x · ${fmt(i.price)}</div>
                </div>
                <div class="ci-price">${fmt(i.price * i.qty)}</div>
            </div>`).join('');
}

function fmt(n) { return 'Rp ' + n.toLocaleString('id-ID'); }

function selectPay(el, method) {
    document.querySelectorAll('.pay-option').forEach(o => o.classList.remove('selected'));
    el.classList.add('selected');
    selectedPay = method;
}

function filterCat(el, cat) {
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.menu-item').forEach(item => {
        item.style.display = (cat === 'all' || item.dataset.cat === cat) ? 'flex' : 'none';
    });
}

function searchMenu(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.menu-item').forEach(item => {
        item.style.display = item.dataset.name.includes(q) ? 'flex' : 'none';
    });
}

async function goToPayment() {
    const items = Object.entries(cart);
    if (!items.length) { alert('Tambah menu dulu brader! 🛒'); return; }

    const btn = document.getElementById('orderBtn');
    btn.disabled = true;
    btn.innerHTML = '<span>⏳ Memproses...</span>';

    try {
        const res = await fetch(`/s/${TABLE}/order`, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
            body: JSON.stringify({
                items: items.map(([id,i]) => ({ id:parseInt(id), qty:i.qty })),
                payment_method: selectedPay,
                notes: document.getElementById('notesInput').value,
            }),
        });
        const data = await res.json();
        if (data.success) {
            orderId = data.order_id;
            showPaymentOverlay();
        } else {
            alert('Gagal order: ' + (data.message || 'Coba lagi.'));
            btn.disabled = false;
            btn.innerHTML = `<span>🛒 Lanjut Pembayaran</span><span class="btn-price">${fmt(grandTotal)}</span>`;
        }
    } catch(e) {
        alert('Koneksi error, coba lagi.');
        btn.disabled = false;
        btn.innerHTML = `<span>🛒 Lanjut Pembayaran</span><span class="btn-price">${fmt(grandTotal)}</span>`;
    }
}

function showPaymentOverlay() {
    const oid = String(orderId).padStart(4,'0');
    document.getElementById('payOrderNum').textContent = `# ORDER-${oid}`;
    document.getElementById('payTotal').textContent    = fmt(grandTotal);

    const box = document.getElementById('payMethodContent');
    const totalFmt = fmt(grandTotal);

    if (selectedPay === 'qris') {
        box.innerHTML = `
            <div class="pay-method-title">🔲 Scan QRIS untuk Bayar</div>
            <div class="qris-box">
                ${KAFE.qris_url
                    ? `<img class="qris-img" src="${KAFE.qris_url}" alt="QRIS ${KAFE.nama}">`
                    : `<div class="qris-placeholder">
                        <div class="qris-placeholder-icon">🔲</div>
                        <div class="qris-placeholder-text">Tempel gambar QRIS kafe lo di sini<br>(isi KAFE.qris_url di kode)</div>
                       </div>`}
                <div class="qris-label">☕ ${KAFE.nama}</div>
                <div class="qris-total">${totalFmt}</div>
                <div class="qris-note">Scan QR di atas pakai GoPay, OVO, DANA, ShopeePay, atau m-banking manapun</div>
            </div>`;
    } else if (selectedPay === 'dana') {
        box.innerHTML = `
            <div class="pay-method-title">💙 Transfer DANA</div>
            <div class="instruksi-box">
                <div class="instruksi-icon">💙</div>
                <div class="instruksi-title">Kirim ke nomor DANA:</div>
                <div class="instruksi-nomor">${KAFE.dana}</div>
                <div class="instruksi-atas-nama">a/n ${KAFE.nama}</div>
                <div class="instruksi-step"><div class="step-num">1</div><div class="step-text">Buka aplikasi DANA</div></div>
                <div class="instruksi-step"><div class="step-num">2</div><div class="step-text">Kirim ke <strong>${KAFE.dana}</strong></div></div>
                <div class="instruksi-step"><div class="step-num">3</div><div class="step-text">Nominal <strong>${totalFmt}</strong></div></div>
                <div class="instruksi-step"><div class="step-num">4</div><div class="step-text">Klik tombol konfirmasi di bawah setelah bayar</div></div>
            </div>`;
    } else if (selectedPay === 'gopay') {
        box.innerHTML = `
            <div class="pay-method-title">💚 Transfer GoPay</div>
            <div class="instruksi-box">
                <div class="instruksi-icon">💚</div>
                <div class="instruksi-title">Kirim ke nomor GoPay:</div>
                <div class="instruksi-nomor">${KAFE.gopay}</div>
                <div class="instruksi-atas-nama">a/n ${KAFE.nama}</div>
                <div class="instruksi-step"><div class="step-num">1</div><div class="step-text">Buka aplikasi Gojek</div></div>
                <div class="instruksi-step"><div class="step-num">2</div><div class="step-text">GoPay → Kirim ke <strong>${KAFE.gopay}</strong></div></div>
                <div class="instruksi-step"><div class="step-num">3</div><div class="step-text">Nominal <strong>${totalFmt}</strong></div></div>
                <div class="instruksi-step"><div class="step-num">4</div><div class="step-text">Klik tombol konfirmasi di bawah setelah bayar</div></div>
            </div>`;
    } else {
        const oid = String(orderId).padStart(4,'0');
        box.innerHTML = `
            <div class="pay-method-title">💵 Bayar Cash di Kasir</div>
            <div class="instruksi-box">
                <div class="instruksi-icon">💵</div>
                <div class="instruksi-title">Tunjukkan ke kasir:</div>
                <div class="instruksi-nomor"># ORDER-${oid}</div>
                <div class="instruksi-atas-nama">Total: ${totalFmt}</div>
                <div class="instruksi-step"><div class="step-num">1</div><div class="step-text">Tunjukkan nomor order ke kasir</div></div>
                <div class="instruksi-step"><div class="step-num">2</div><div class="step-text">Bayar tunai <strong>${totalFmt}</strong></div></div>
                <div class="instruksi-step"><div class="step-num">3</div><div class="step-text">Klik konfirmasi setelah kasir terima pembayaran</div></div>
            </div>`;
    }

    document.getElementById('paymentOverlay').classList.add('open');
}

function closePayment() {
    document.getElementById('paymentOverlay').classList.remove('open');
    const btn = document.getElementById('orderBtn');
    btn.disabled = false;
    btn.innerHTML = `<span>🛒 Lanjut Pembayaran</span><span class="btn-price">${fmt(grandTotal)}</span>`;
}

function confirmPayment() {
    const oid = String(orderId).padStart(4,'0');
    document.getElementById('paymentOverlay').classList.remove('open');
    currentOrderId = orderId;
    document.getElementById('successBadge').textContent = `# ORDER-${oid}`;
    document.getElementById('successOverlay').style.display = 'flex';
}

function resetPage() { location.reload(); }

function cekStatusOrder() {
    window.location.href = `/s/${TABLE}/tracking?order=${currentOrderId}`;
}
</script>
@endpush