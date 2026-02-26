@extends('layouts.app')

@section('title', 'Menu - Meja ' . $table->table_number)

@push('styles')
<style>
    body { padding-bottom: 100px; }

    .page-header {
        position: sticky; top: 0; z-index: 50;
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        background: rgba(10,10,15,0.95);
        border-bottom: 1px solid var(--border); padding: 14px 20px;
    }
    .header-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .header-brand { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 800; }
    .table-badge {
        background: var(--accent); color: #000;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem; font-weight: 700; padding: 4px 12px; border-radius: 100px;
    }
    .cat-tabs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 2px; scrollbar-width: none; }
    .cat-tabs::-webkit-scrollbar { display: none; }
    .cat-tab {
        display: flex; align-items: center; gap: 5px;
        background: var(--surface2); border: 1px solid var(--border);
        color: var(--muted); font-size: 0.78rem; font-weight: 600;
        padding: 6px 14px; border-radius: 100px; cursor: pointer;
        white-space: nowrap; transition: all 0.2s; flex-shrink: 0;
    }
    .cat-tab.active, .cat-tab:hover { background: var(--accent); border-color: var(--accent); color: #000; }

    .search-wrap { padding: 14px 20px 0; }
    .search-input {
        width: 100%; background: var(--surface);
        border: 1px solid var(--border); border-radius: 12px;
        padding: 11px 16px 11px 42px; color: var(--text);
        font-family: 'Space Grotesk', sans-serif; font-size: 0.88rem;
        outline: none; transition: border-color 0.2s;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b6b7e' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: 13px center;
    }
    .search-input:focus { border-color: var(--accent); }
    .search-input::placeholder { color: var(--muted); }

    .promo-banner {
        margin: 14px 20px;
        background: linear-gradient(135deg, rgba(124,106,255,0.12), rgba(212,255,0,0.04));
        border: 1px solid rgba(124,106,255,0.25);
        border-radius: 14px; padding: 12px 16px;
        display: flex; align-items: center; gap: 12px;
    }
    .promo-icon { font-size: 1.6rem; }
    .promo-text { flex: 1; }
    .promo-title { font-weight: 700; font-size: 0.85rem; }
    .promo-sub { font-size: 0.72rem; color: var(--muted); }
    .promo-badge {
        background: var(--accent3); color: white;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem; font-weight: 700; padding: 3px 8px; border-radius: 6px;
    }

    .menu-section { padding: 0 20px; }
    .section-head { display: flex; align-items: center; justify-content: space-between; margin: 20px 0 12px; }
    .section-title { font-family: 'Syne', sans-serif; font-size: 1.1rem; font-weight: 800; }
    .section-count { font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: var(--muted); }

    .menu-list { display: flex; flex-direction: column; gap: 10px; }

    .menu-item {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; padding: 12px; display: flex; gap: 12px; transition: all 0.2s;
    }
    .menu-item:hover { border-color: rgba(255,255,255,0.1); background: var(--surface2); }
    .menu-item.sold-out { opacity: 0.5; pointer-events: none; }

    .item-thumb {
        width: 72px; height: 72px; border-radius: 10px; flex-shrink: 0;
        background: var(--surface2); display: flex; align-items: center;
        justify-content: center; font-size: 2rem; overflow: hidden;
    }
    .item-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }

    .item-info { flex: 1; min-width: 0; }
    .item-name { font-weight: 700; font-size: 0.92rem; margin-bottom: 2px; }
    .item-desc {
        font-size: 0.73rem; color: var(--muted); line-height: 1.4; margin-bottom: 8px;
        overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    }
    .item-bottom { display: flex; align-items: center; justify-content: space-between; }
    .item-price { font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 800; color: var(--accent); }

    .qty-control {
        display: flex; align-items: center;
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: 9px; overflow: hidden;
    }
    .qty-btn {
        background: transparent; border: none; color: var(--text);
        width: 30px; height: 30px; font-size: 1rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: background 0.15s;
    }
    .qty-btn:hover { background: var(--border); }
    .qty-num { font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; font-weight: 700; min-width: 26px; text-align: center; }

    .add-btn {
        background: var(--accent); border: none; color: #000;
        font-weight: 700; font-size: 0.78rem; padding: 7px 14px;
        border-radius: 9px; cursor: pointer; transition: all 0.2s;
        font-family: 'Space Grotesk', sans-serif;
    }
    .add-btn:hover { transform: scale(1.04); }

    .divider {
        margin: 28px 20px; border: none; border-top: 1px solid var(--border); position: relative;
    }
    .divider::after {
        content: '🛒 Pesanan Lo';
        position: absolute; top: -10px; left: 50%; transform: translateX(-50%);
        background: var(--bg); padding: 0 12px;
        font-size: 0.75rem; color: var(--muted);
        font-family: 'JetBrains Mono', monospace; white-space: nowrap;
    }

    .checkout-section { padding: 0 20px; }

    .cart-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; padding: 16px; margin-bottom: 12px;
    }
    .cart-title { font-family: 'Syne', sans-serif; font-size: 0.95rem; font-weight: 800; margin-bottom: 12px; }
    .cart-empty { text-align: center; padding: 20px; color: var(--muted); font-size: 0.82rem; }

    .cart-item { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--border); }
    .cart-item:last-child { border-bottom: none; padding-bottom: 0; }
    .ci-emoji {
        width: 36px; height: 36px; background: var(--surface2);
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .ci-info { flex: 1; }
    .ci-name { font-weight: 600; font-size: 0.85rem; }
    .ci-sub { font-size: 0.7rem; color: var(--muted); }
    .ci-price { font-family: 'Syne', sans-serif; font-size: 0.9rem; font-weight: 800; color: var(--accent); white-space: nowrap; }

    .pay-options { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .pay-option {
        background: var(--surface2); border: 2px solid var(--border);
        border-radius: 12px; padding: 10px 12px; cursor: pointer; transition: all 0.2s; position: relative;
    }
    .pay-option.selected { border-color: var(--accent); background: rgba(212,255,0,.05); }
    .pay-icon { font-size: 1.4rem; margin-bottom: 2px; }
    .pay-name { font-weight: 700; font-size: 0.82rem; }
    .pay-sub { font-size: 0.65rem; color: var(--muted); }
    .pay-check {
        display: none; position: absolute; top: 6px; right: 6px;
        width: 14px; height: 14px; background: var(--accent); border-radius: 50%;
        align-items: center; justify-content: center; font-size: 0.5rem; color: #000; font-weight: 900;
    }
    .pay-option.selected .pay-check { display: flex; }

    .notes-input {
        width: 100%; background: var(--surface2);
        border: 1px solid var(--border); border-radius: 10px;
        padding: 10px 12px; color: var(--text);
        font-family: 'Space Grotesk', sans-serif; font-size: 0.85rem;
        resize: none; outline: none; transition: border-color 0.2s; min-height: 64px;
    }
    .notes-input:focus { border-color: var(--accent); }
    .notes-input::placeholder { color: var(--muted); }

    .summary-row { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 0.85rem; }
    .summary-row .lbl { color: var(--muted); }
    .summary-row .val { font-family: 'JetBrains Mono', monospace; }
    .summary-row.total { border-top: 1px solid var(--border); margin-top: 6px; padding-top: 12px; }
    .summary-row.total .lbl { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 0.95rem; color: var(--text); }
    .summary-row.total .val { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.2rem; color: var(--accent); }

    .order-wrap {
        position: fixed; bottom: 0; left: 0; right: 0; padding: 12px 20px;
        background: linear-gradient(to top, var(--bg) 70%, transparent); z-index: 99;
    }
    .order-btn {
        width: 100%; display: flex; align-items: center; justify-content: space-between;
        background: var(--accent); color: #000; font-weight: 800; font-size: 0.95rem;
        padding: 16px 22px; border-radius: 14px; border: none; cursor: pointer;
        transition: all 0.2s; font-family: 'Syne', sans-serif;
    }
    .order-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(212,255,0,.28); }
    .order-btn:disabled { opacity: .5; cursor: not-allowed; transform: none; }
    .btn-price {
        background: rgba(0,0,0,.18); padding: 4px 12px; border-radius: 100px;
        font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;
    }
    .spinner {
        display: none; width: 18px; height: 18px;
        border: 2px solid rgba(0,0,0,.3); border-top-color: #000;
        border-radius: 50%; animation: spin .7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    .success-overlay {
        position: fixed; inset: 0; background: rgba(10,10,15,.97);
        display: none; flex-direction: column; align-items: center; justify-content: center;
        z-index: 999; text-align: center; padding: 40px; backdrop-filter: blur(24px);
    }
    .success-icon { font-size: 5rem; margin-bottom: 20px; animation: popIn .5s cubic-bezier(.34,1.56,.64,1) forwards; }
    @keyframes popIn { from{transform:scale(0);opacity:0} to{transform:scale(1);opacity:1} }
    .success-title { font-family: 'Syne', sans-serif; font-size: 2rem; font-weight: 800; margin-bottom: 8px; }
    .success-sub { color: var(--muted); font-size: 0.9rem; line-height: 1.7; margin-bottom: 24px; }
    .order-badge {
        font-family: 'JetBrains Mono', monospace; font-size: 0.75rem;
        color: var(--accent); background: rgba(212,255,0,.08);
        border: 1px solid rgba(212,255,0,.2); padding: 6px 16px; border-radius: 100px; margin-bottom: 24px;
    }
    .back-menu-btn {
        background: var(--accent); color: #000; font-weight: 700;
        padding: 12px 28px; border-radius: 100px; border: none; cursor: pointer;
        font-size: 0.9rem; font-family: 'Space Grotesk', sans-serif;
    }
</style>
@endpush

@section('content')

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
        <div class="section-count">{{ $products->count() }} items</div>
    </div>
    <div class="menu-list" id="menuList">
        @forelse($products as $product)
        <div class="menu-item {{ !$product->is_ready ? 'sold-out' : '' }}"
                data-cat="{{ $product->category }}"
                data-name="{{ strtolower($product->name) }}">
            <div class="item-thumb">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                @else
                    @switch($product->category)
                        @case('coffee') ☕ @break
                        @case('noncoffee') 🧋 @break
                        @case('food') 🍞 @break
                        @case('snack') 🍿 @break
                        @default ☕
                    @endswitch
                @endif
            </div>
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
                    <button class="add-btn" disabled style="opacity:.4;cursor:not-allowed;background:var(--surface2);color:var(--muted);">Habis</button>
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
    <button class="order-btn" id="orderBtn" onclick="placeOrder()">
        <span>⚡ Pesan Sekarang</span>
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="btn-price" id="btnTotal">Rp 2.000</span>
            <div class="spinner" id="spinner"></div>
        </div>
    </button>
</div>

<div class="success-overlay" id="successOverlay">
    <div class="success-icon">🎉</div>
    <div class="success-title">Order Masuk!</div>
    <div class="success-sub">Pesanan sudah diterima.<br>Ditunggu kami lagi siapkan pesanan anda sekarang~</div>
    <div class="order-badge" id="orderBadge"># ORDER-0000</div>
    <button class="back-menu-btn" onclick="resetPage()">+ Order Lagi</button>
</div>

@endsection

@push('scripts')
<script>
    const TABLE_NUMBER = '{{ $table->table_number }}';
    const CSRF_TOKEN   = '{{ csrf_token() }}';
    const SERVICE_FEE  = 2000;
    const EMOJI = { coffee: '☕', noncoffee: '🧋', food: '🍞', snack: '🍿' };

    let cart = {};
    let selectedPayment = 'qris';

    function addItem(id, name, price, category) {
        if (!cart[id]) cart[id] = { name, price, category, qty: 0 };
        cart[id].qty++;
        const ctrl = document.getElementById('ctrl-' + id);
        ctrl.innerHTML = `
            <div class="qty-control">
                <button class="qty-btn" onclick="removeItem(${id}, ${price}, '${category}', '${name}')">−</button>
                <span class="qty-num" id="qty-${id}">${cart[id].qty}</span>
                <button class="qty-btn" onclick="addItemById(${id})">+</button>
            </div>`;
        updateUI();
    }

    function addItemById(id) {
        cart[id].qty++;
        document.getElementById('qty-' + id).textContent = cart[id].qty;
        updateUI();
    }

    function removeItem(id, price, category, name) {
        if (!cart[id]) return;
        cart[id].qty--;
        if (cart[id].qty <= 0) {
            delete cart[id];
            document.getElementById('ctrl-' + id).innerHTML =
                `<button class="add-btn" onclick="addItem(${id}, '${name}', ${price}, '${category}')">+ Tambah</button>`;
        } else {
            document.getElementById('qty-' + id).textContent = cart[id].qty;
        }
        updateUI();
    }

    function updateUI() {
        const items = Object.entries(cart);
        const subtotal = items.reduce((s, [,i]) => s + i.price * i.qty, 0);
        const count    = items.reduce((s, [,i]) => s + i.qty, 0);
        const total    = subtotal + SERVICE_FEE;

        document.getElementById('itemCount').textContent    = count;
        document.getElementById('subtotalVal').textContent  = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('grandTotalVal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('btnTotal').textContent     = 'Rp ' + total.toLocaleString('id-ID');

        const container = document.getElementById('cartContainer');
        if (items.length === 0) {
            container.innerHTML = '<div class="cart-empty">Belum ada item — tambah menu dulu di atas 👆</div>';
        } else {
            container.innerHTML = items.map(([id, item]) => `
                <div class="cart-item">
                    <div class="ci-emoji">${EMOJI[item.category] || '☕'}</div>
                    <div class="ci-info">
                        <div class="ci-name">${item.name}</div>
                        <div class="ci-sub">${item.qty}x · Rp ${item.price.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="ci-price">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</div>
                </div>`).join('');
        }
    }

    function selectPay(el, method) {
        document.querySelectorAll('.pay-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        selectedPayment = method;
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

    async function placeOrder() {
        const items = Object.entries(cart);
        if (items.length === 0) {
            alert('Tambah menu dulu brader! 🛒');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

        const btn = document.getElementById('orderBtn');
        const spinner = document.getElementById('spinner');
        btn.disabled = true;
        spinner.style.display = 'block';

        try {
            const res = await fetch(`/s/${TABLE_NUMBER}/order`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    items: items.map(([id, item]) => ({ id: parseInt(id), qty: item.qty })),
                    payment_method: selectedPayment,
                    notes: document.getElementById('notesInput').value,
                }),
            });

            const data = await res.json();

            if (data.success) {
                const oid = String(data.order_id).padStart(4, '0');
                document.getElementById('orderBadge').textContent = `# ORDER-${oid}`;
                document.getElementById('successOverlay').style.display = 'flex';
            } else {
                alert('Gagal order: ' + (data.message || 'Coba lagi ya.'));
                btn.disabled = false;
                spinner.style.display = 'none';
            }
        } catch (err) {
            alert('Koneksi error, coba lagi.');
            btn.disabled = false;
            spinner.style.display = 'none';
        }
    }

    function resetPage() {
        cart = {};
        document.getElementById('successOverlay').style.display = 'none';
        document.getElementById('orderBtn').disabled = false;
        document.getElementById('spinner').style.display = 'none';
        document.getElementById('notesInput').value = '';
        updateUI();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        location.reload();
    }
</script>
@endpush