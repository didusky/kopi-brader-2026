@extends('layouts.app')

@section('title', 'Checkout - Meja ' . $table->number)

@push('styles')
<style>
    body { padding-bottom: 140px; }

    .page-header {
        position: sticky; top: 0; z-index: 50;
        backdrop-filter: blur(20px);
        background: rgba(10,10,15,0.92);
        border-bottom: 1px solid var(--border);
        padding: 14px 20px;
        display: flex; align-items: center; justify-content: space-between;
    }

    .back-btn {
        display: flex; align-items: center; gap: 6px;
        color: var(--muted); font-size: 0.85rem;
        text-decoration: none; transition: color 0.2s;
    }
    .back-btn:hover { color: var(--text); }

    .header-title { font-family: 'Syne', sans-serif; font-size: 1.05rem; font-weight: 800; }

    .table-badge {
        background: var(--accent); color: #000;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem; font-weight: 700;
        padding: 4px 12px; border-radius: 100px;
    }

    /* MAIN */
    .main-pad { padding: 16px 20px; max-width: 480px; margin: 0 auto; }

    /* CARD */
    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px; padding: 18px;
        margin-bottom: 14px;
    }
    .card-title {
        font-family: 'Syne', sans-serif;
        font-size: 0.95rem; font-weight: 800;
        margin-bottom: 14px;
    }

    /* CART ITEMS */
    .cart-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 0; border-bottom: 1px solid var(--border);
    }
    .cart-item:last-child { border-bottom: none; padding-bottom: 0; }
    .ci-thumb {
        width: 44px; height: 44px; border-radius: 10px;
        background: var(--surface2); display: flex;
        align-items: center; justify-content: center;
        font-size: 1.6rem; flex-shrink: 0;
    }
    .ci-info { flex: 1; }
    .ci-name { font-weight: 600; font-size: 0.88rem; }
    .ci-sub { font-size: 0.72rem; color: var(--muted); margin-top: 1px; }
    .ci-price { font-family: 'Syne', sans-serif; font-size: 0.95rem; font-weight: 800; color: var(--accent); white-space: nowrap; }

    /* EMPTY CART */
    .empty-cart {
        text-align: center; padding: 40px 20px; color: var(--muted);
    }

    /* NOTES */
    .notes-input {
        width: 100%; background: var(--surface2);
        border: 1px solid var(--border); border-radius: 10px;
        padding: 11px 13px; color: var(--text);
        font-family: 'Space Grotesk', sans-serif;
        font-size: 0.85rem; resize: none; outline: none;
        transition: border-color 0.2s; min-height: 72px;
    }
    .notes-input:focus { border-color: var(--accent); }
    .notes-input::placeholder { color: var(--muted); }

    /* PAYMENT */
    .pay-options {
        display: grid; grid-template-columns: 1fr 1fr; gap: 8px;
    }
    .pay-option {
        background: var(--surface2);
        border: 2px solid var(--border);
        border-radius: 12px; padding: 12px;
        cursor: pointer; transition: all 0.2s;
        position: relative;
    }
    .pay-option:hover { border-color: rgba(255,255,255,.12); }
    .pay-option.selected { border-color: var(--accent); background: rgba(212,255,0,.05); }
    .pay-icon { font-size: 1.6rem; margin-bottom: 4px; }
    .pay-name { font-weight: 700; font-size: 0.85rem; }
    .pay-sub { font-size: 0.68rem; color: var(--muted); }
    .pay-check {
        display: none; position: absolute;
        top: 8px; right: 8px;
        width: 16px; height: 16px;
        background: var(--accent); border-radius: 50%;
        align-items: center; justify-content: center;
        font-size: 0.55rem; color: #000; font-weight: 900;
    }
    .pay-option.selected .pay-check { display: flex; }

    /* SUMMARY */
    .summary-row {
        display: flex; justify-content: space-between;
        align-items: center; padding: 7px 0; font-size: 0.85rem;
    }
    .summary-row .lbl { color: var(--muted); }
    .summary-row .val { font-family: 'JetBrains Mono', monospace; }
    .summary-row.total {
        border-top: 1px solid var(--border);
        margin-top: 6px; padding-top: 14px;
    }
    .summary-row.total .lbl { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.95rem; color: var(--text); }
    .summary-row.total .val { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.25rem; color: var(--accent); }

    /* CHECKOUT BTN */
    .checkout-wrap {
        position: fixed; bottom: 0; left: 0; right: 0;
        padding: 14px 20px;
        background: linear-gradient(to top, var(--bg) 60%, transparent);
        z-index: 99;
    }
    .checkout-btn {
        width: 100%; max-width: 480px; margin: 0 auto; display: flex;
        align-items: center; justify-content: space-between;
        background: var(--accent); color: #000;
        font-weight: 800; font-size: 0.95rem;
        padding: 16px 22px; border-radius: 14px; border: none;
        cursor: pointer; transition: all 0.2s;
        font-family: 'Syne', sans-serif;
    }
    .checkout-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 36px rgba(212,255,0,.28); }
    .checkout-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    .btn-price {
        background: rgba(0,0,0,.18);
        padding: 5px 12px; border-radius: 100px;
        font-family: 'JetBrains Mono', monospace; font-size: 0.82rem;
    }

    /* SUCCESS OVERLAY */
    .success-overlay {
        position: fixed; inset: 0;
        background: rgba(10,10,15,.96);
        display: none; flex-direction: column;
        align-items: center; justify-content: center;
        z-index: 999; text-align: center; padding: 40px;
        backdrop-filter: blur(24px);
    }
    .success-icon { font-size: 5rem; margin-bottom: 22px; animation: popIn .5s cubic-bezier(.34,1.56,.64,1) forwards; }
    @keyframes popIn { from{transform:scale(0);opacity:0} to{transform:scale(1);opacity:1} }
    .success-title { font-family: 'Syne', sans-serif; font-size: 2.2rem; font-weight: 800; margin-bottom: 10px; }
    .success-sub { color: var(--muted); font-size: 0.95rem; line-height: 1.7; margin-bottom: 28px; }
    .order-badge {
        font-family: 'JetBrains Mono', monospace; font-size: 0.78rem;
        color: var(--accent); background: rgba(212,255,0,.08);
        border: 1px solid rgba(212,255,0,.2); padding: 7px 18px;
        border-radius: 100px; margin-bottom: 28px;
    }
    .back-btn-success {
        background: var(--accent); color: #000; font-weight: 700;
        padding: 13px 30px; border-radius: 100px;
        border: none; cursor: pointer; font-size: 0.92rem;
        font-family: 'Space Grotesk', sans-serif;
        text-decoration: none;
    }

    /* SPINNER */
    .spinner {
        display: none; width: 20px; height: 20px;
        border: 2px solid rgba(0,0,0,.3); border-top-color: #000;
        border-radius: 50%; animation: spin .7s linear infinite;
    }
    @keyframes spin { to{transform:rotate(360deg)} }
</style>
@endpush

@section('content')

<div class="page-header">
    <a href="/s/{{ $table->number }}" class="back-btn">← Menu</a>
    <div class="header-title">Checkout 🛒</div>
    <div class="table-badge">MEJA {{ $table->number }}</div>
</div>

<div class="main-pad">

    {{-- CART ITEMS (rendered by JS from sessionStorage) --}}
    <div class="card">
        <div class="card-title">📋 Pesanan Anda</div>
        <div id="cartItemsContainer">
            <div class="empty-cart">
                <div style="font-size:2.5rem;margin-bottom:10px">🛒</div>
                <div>Cart kosong — <a href="/s/{{ $table->number }}" style="color:var(--accent)">balik ke menu</a></div>
            </div>
        </div>
    </div>

    {{-- NOTES --}}
    <div class="card">
        <div class="card-title">📝 Catatan</div>
        <textarea class="notes-input" id="notesInput"
            placeholder="Contoh: kurang manis, extra oat milk, dll..."></textarea>
    </div>

    {{-- PAYMENT METHOD --}}
    <div class="card">
        <div class="card-title">💳 Metode Pembayaran</div>
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

    {{-- SUMMARY --}}
    <div class="card">
        <div class="card-title">🧾 Ringkasan</div>
        <div class="summary-row">
            <span class="lbl">Subtotal (<span id="itemCount">0</span> item)</span>
            <span class="val" id="subtotal">Rp 0</span>
        </div>
        <div class="summary-row">
            <span class="lbl">Biaya Layanan</span>
            <span class="val">Rp 2.000</span>
        </div>
        <div class="summary-row total">
            <span class="lbl">Total</span>
            <span class="val" id="grandTotal">Rp 2.000</span>
        </div>
    </div>

</div>

{{-- CHECKOUT BUTTON --}}
<div class="checkout-wrap">
    <button class="checkout-btn" id="checkoutBtn" onclick="placeOrder()">
        <span>⚡ Pesan Sekarang</span>
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="btn-price" id="btnTotal">Rp 0</span>
            <div class="spinner" id="spinner"></div>
        </div>
    </button>
</div>

{{-- SUCCESS OVERLAY --}}
<div class="success-overlay" id="successOverlay">
    <div class="success-icon">🎉</div>
    <div class="success-title">Order Masuk!</div>
    <div class="success-sub">Pesanan lo udah diterima.<br>Barista lagi nyiapin kopi lo sekarang~</div>
    <div class="order-badge" id="orderBadge"># ORDER-XXX</div>
    <a class="back-btn-success" href="/s/{{ $table->number }}">← Balik ke Menu</a>
</div>

@endsection

@push('scripts')
<script>
    const TABLE_NUMBER = '{{ $table->number }}';
    const CSRF_TOKEN = '{{ csrf_token() }}';
    const SERVICE_FEE = 2000;

    let cart = {};
    let selectedPayment = 'qris';

    // Load cart
    const saved = sessionStorage.getItem('kb_cart_' + TABLE_NUMBER);
    if (saved) cart = JSON.parse(saved);
    renderCart();

    function renderCart() {
        const container = document.getElementById('cartItemsContainer');
        const items = Object.entries(cart);

        if (items.length === 0) {
            container.innerHTML = `<div class="empty-cart">
                <div style="font-size:2.5rem;margin-bottom:10px">🛒</div>
                <div>Cart kosong — <a href="/s/${TABLE_NUMBER}" style="color:var(--accent)">balik ke menu</a></div>
            </div>`;
            updateSummary();
            return;
        }

        const emojis = { default: '☕' };

        container.innerHTML = items.map(([id, item]) => `
            <div class="cart-item">
                <div class="ci-thumb">${emojis[id] || '☕'}</div>
                <div class="ci-info">
                    <div class="ci-name">${item.name}</div>
                    <div class="ci-sub">${item.qty}x • Rp ${item.price.toLocaleString('id-ID')}</div>
                </div>
                <div class="ci-price">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</div>
            </div>
        `).join('');

        updateSummary();
    }

    function updateSummary() {
        const subtotal = Object.values(cart).reduce((s, i) => s + i.price * i.qty, 0);
        const count = Object.values(cart).reduce((s, i) => s + i.qty, 0);
        const total = subtotal + SERVICE_FEE;

        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('itemCount').textContent = count;
        document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('btnTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    function selectPay(el, method) {
        document.querySelectorAll('.pay-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        selectedPayment = method;
    }

    async function placeOrder() {
        const items = Object.entries(cart);
        if (items.length === 0) {
            alert('Cart masih kosong brader! Tambahkan menu dulu.');
            return;
        }

        const btn = document.getElementById('checkoutBtn');
        const spinner = document.getElementById('spinner');
        btn.disabled = true;
        spinner.style.display = 'block';

        const payload = {
            items: items.map(([id, item]) => ({ id: parseInt(id), qty: item.qty })),
            payment_method: selectedPayment,
            notes: document.getElementById('notesInput').value,
        };

        try {
            const res = await fetch(`/s/${TABLE_NUMBER}/order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await res.json();

            if (data.success) {
                sessionStorage.removeItem('kb_cart_' + TABLE_NUMBER);
                const oid = String(data.order_id).padStart(4, '0');
                document.getElementById('orderBadge').textContent = `# ORDER-${oid}`;
                document.getElementById('successOverlay').style.display = 'flex';
            } else {
                alert('Gagal order: ' + (data.message || 'Coba lagi ya brader.'));
                btn.disabled = false;
                spinner.style.display = 'none';
            }
        } catch (err) {
            alert('Koneksi error. Pastiin internet lo nyala, terus coba lagi.');
            btn.disabled = false;
            spinner.style.display = 'none';
        }
    }
</script>
@endpush