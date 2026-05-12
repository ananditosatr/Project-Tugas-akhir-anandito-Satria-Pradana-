@extends('layouts.app')

@section('title', 'Order Menu - F&B POS Critasena')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">

    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-40">
        <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center shadow">
                    <span class="text-sm font-bold text-white">POS</span>
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-900 dark:text-white">Order Menu</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Scan QR & Order</p>
                </div>
            </div>
            <button onclick="toggleDarkMode()" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                <svg id="icon-moon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
                <svg id="icon-sun" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
                </svg>
            </button>
        </div>
    </header>

    <!-- Category Filter -->
    <div class="max-w-2xl mx-auto px-4 pt-4 pb-2">
        <div class="flex space-x-2 overflow-x-auto pb-2 scrollbar-hide">
            <button onclick="filterCategory('all', this)"
                class="category-btn active-cat flex-shrink-0 px-4 py-2 bg-red-600 text-white rounded-full text-sm font-medium whitespace-nowrap">
                Semua
            </button>
            @foreach($categories as $category)
            <button onclick="filterCategory({{ $category->id }}, this)"
                class="category-btn flex-shrink-0 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 whitespace-nowrap transition">
                {{ $category->name }}
            </button>
            @endforeach
        </div>
    </div>

    <!-- Menu Grid -->
    <main class="max-w-2xl mx-auto px-4 pb-28">
        <div class="grid grid-cols-2 gap-3">
            @foreach($menus as $menu)
            <div class="menu-card bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition"
                data-category="{{ $menu->category_id }}">
                <div class="aspect-square bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    @if($menu->image_base64)
                    <img src="{{ $menu->image_base64 }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                    @else
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1M4.22 4.22l.71.71m13.66 13.66.71.71M1 12h1m20 0h1M4.22 19.78l.71-.71M19.07 4.93l.71-.71M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $menu->name }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 line-clamp-2">{{ $menu->description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-red-600 dark:text-red-400 font-bold text-sm">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        <button onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})"
                            class="w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-full flex items-center justify-center transition shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>

    <!-- Cart FAB -->
    <div id="cart-fab" class="hidden fixed bottom-6 right-6 z-50">
        <button onclick="openCart()"
            class="relative w-14 h-14 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-xl flex items-center justify-center transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span id="cart-count" class="absolute -top-1 -right-1 w-5 h-5 bg-white text-red-600 text-xs font-bold rounded-full flex items-center justify-center shadow">0</span>
        </button>
    </div>

</div>

<!-- ===== MODALS ===== -->

<!-- Cart Modal -->
<div id="cart-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-end sm:items-center justify-center">
    <div id="cart-content" class="bg-white dark:bg-gray-800 w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl max-h-[85vh] flex flex-col">
        <!-- Cart header & body injected by JS -->
    </div>
</div>

<!-- Step 1: QRIS Modal — scan & bayar dulu -->
<div id="checkout-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-md overflow-y-auto max-h-[90vh]">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <button onclick="closeCheckout()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Pembayaran QRIS</h2>
                <div class="w-5"></div>
            </div>

            <!-- Total -->
            <div class="text-center mb-5">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total yang harus dibayar</p>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-0.5">Rp <span id="display-total"></span></p>
            </div>

            <!-- QRIS Static Image -->
            <div class="flex flex-col items-center mb-4">
                <div class="bg-white border-2 border-gray-200 dark:border-gray-600 rounded-2xl p-3 shadow-inner">
                    <img src="/images/qris_simulasi.png" alt="QRIS Critasena" class="w-56 h-56 object-contain">
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 text-center">
                    Scan menggunakan m-banking atau e-wallet kamu
                </p>
            </div>

            <!-- Upload Bukti Bayar -->
            <div id="upload-area" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-4 text-center mb-4 cursor-pointer hover:border-red-400 transition"
                onclick="document.getElementById('proof-file-input').click()">
                <input type="file" id="proof-file-input" accept="image/*" class="hidden" onchange="handleProofFile(event)">
                <div id="upload-placeholder">
                    <svg class="w-8 h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Upload Bukti Bayar</p>
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG maks 2MB</p>
                </div>
                <div id="upload-preview" class="hidden">
                    <img id="preview-img" class="max-h-32 mx-auto rounded-lg shadow mb-2">
                    <p class="text-xs text-green-600 dark:text-green-400 font-medium">Bukti terpilih — klik untuk ganti</p>
                </div>
            </div>

            <!-- Tombol Sudah Bayar — aktif setelah upload -->
            <button id="sudah-bayar-btn" onclick="proceedToPayment()" disabled
                class="w-full py-3.5 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-xl font-bold text-sm cursor-not-allowed transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Saya Sudah Bayar
            </button>
        </div>
    </div>
</div>

<!-- Step 2: Form Data Diri — setelah bayar -->
<div id="payment-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-md p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <button onclick="backToQRIS()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </button>
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Data Pemesan</h2>
            <div class="w-16"></div>
        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 -mt-2">
            Isi data di bawah agar pesananmu bisa diproses kasir.
        </p>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                Nama <span class="text-red-500">*</span>
            </label>
            <input type="text" id="customer-name" required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Masukkan nama kamu">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                No. WhatsApp <span class="text-red-500">*</span>
            </label>
            <input type="tel" id="customer-wa" required
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Contoh: 08123456789">
        </div>
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                Catatan <span class="text-xs text-gray-400">(opsional)</span>
            </label>
            <textarea id="order-notes" rows="2"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                placeholder="Contoh: tidak pakai pedas..."></textarea>
        </div>

        <button id="submit-payment-btn" onclick="submitOrder()"
            class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm transition">
            Konfirmasi Pesanan
        </button>
    </div>
</div>

<!-- Tracking Modal -->
<div id="tracking-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-md p-6">
        <div class="text-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Track Pesanan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Order: <span id="track-order-number" class="font-mono font-bold text-red-600 dark:text-red-400"></span></p>
        </div>

        <!-- Status Banner (muncul saat status penting) -->
        <div id="status-banner" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-semibold text-center"></div>

        <div id="status-timeline" class="space-y-4 mb-4">
            <!-- filled by JS -->
        </div>

        <p class="text-xs text-center text-gray-400 dark:text-gray-500 mb-4">Auto refresh setiap 5 detik</p>

        <button onclick="newOrder()"
            class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm transition">
            Pesan Lagi
        </button>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium"></div>
@endsection

@push('scripts')
<script>
// ===== STATE =====
let cart = [];
let currentOrder = null;
let trackingInterval = null;
let proofFile = null;
let proofBase64 = null;

// ===== DARK MODE =====
function toggleDarkMode() {
    const html = document.documentElement;
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        document.getElementById('icon-moon').classList.remove('hidden');
        document.getElementById('icon-sun').classList.add('hidden');
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        document.getElementById('icon-moon').classList.add('hidden');
        document.getElementById('icon-sun').classList.remove('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.documentElement.classList.contains('dark')) {
        document.getElementById('icon-moon').classList.add('hidden');
        document.getElementById('icon-sun').classList.remove('hidden');
    }
    loadCart();
    resumeTrackingIfAny();
});

function resumeTrackingIfAny() {
    const savedOrder = localStorage.getItem('active_order');
    if (!savedOrder) return;

    // Cek dulu ke server apakah order masih aktif
    fetch(`/orders/${savedOrder}`)
        .then(r => r.json())
        .then(order => {
            const terminal = ['completed', 'cancelled'];
            if (terminal.includes(order.status)) {
                localStorage.removeItem('active_order');
                return;
            }
            // Order masih aktif, resume tracking
            currentOrder = order;
            openTracking(savedOrder);
        })
        .catch(() => {});
}

// ===== CATEGORY FILTER =====
function filterCategory(categoryId, btn) {
    document.querySelectorAll('.category-btn').forEach(b => {
        b.classList.remove('bg-red-600', 'text-white', 'active-cat');
        b.classList.add('bg-white', 'dark:bg-gray-800', 'border', 'border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
    });
    btn.classList.add('bg-red-600', 'text-white');
    btn.classList.remove('bg-white', 'dark:bg-gray-800', 'border', 'border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');

    document.querySelectorAll('.menu-card').forEach(card => {
        if (categoryId === 'all' || card.dataset.category == categoryId) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// ===== CART =====
function addToCart(menuId, name, price) {
    const existing = cart.find(i => i.menu_id === menuId);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ menu_id: menuId, name, price, quantity: 1 });
    }
    saveCart();
    updateCartBadge();
    showToast('success', name + ' ditambahkan');
}

function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
    updateCartBadge();
    renderCart();
}

function changeQty(index, delta) {
    cart[index].quantity += delta;
    if (cart[index].quantity <= 0) {
        removeFromCart(index);
        return;
    }
    saveCart();
    renderCart();
}

function updateCartBadge() {
    const total = cart.reduce((s, i) => s + i.quantity, 0);
    const fab = document.getElementById('cart-fab');
    const count = document.getElementById('cart-count');
    if (total > 0) {
        count.textContent = total;
        fab.classList.remove('hidden');
    } else {
        fab.classList.add('hidden');
    }
}

function saveCart() { sessionStorage.setItem('cart', JSON.stringify(cart)); }
function loadCart() {
    const saved = sessionStorage.getItem('cart');
    if (saved) { cart = JSON.parse(saved); updateCartBadge(); }
}

function openCart() {
    document.getElementById('cart-modal').classList.remove('hidden');
    renderCart();
}

function closeCart() {
    document.getElementById('cart-modal').classList.add('hidden');
}

function renderCart() {
    const container = document.getElementById('cart-content');
    const total = cart.reduce((s, i) => s + (i.price * i.quantity), 0);

    let html = `
        <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between flex-shrink-0">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Keranjang</h2>
            <button onclick="closeCart()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;

    if (cart.length === 0) {
        html += `<div class="p-8 text-center flex-1">
            <p class="text-gray-400 dark:text-gray-500 text-sm">Keranjang masih kosong</p>
        </div>`;
    } else {
        html += `<div class="flex-1 overflow-y-auto p-4 space-y-3">`;
        cart.forEach((item, i) => {
            const sub = item.price * item.quantity;
            html += `
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">${item.name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Rp ${item.price.toLocaleString('id-ID')}</p>
                        </div>
                        <button onclick="removeFromCart(${i})" class="text-gray-400 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button onclick="changeQty(${i}, -1)" class="w-7 h-7 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-gray-700 dark:text-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                            <span class="text-sm font-bold text-gray-900 dark:text-white w-4 text-center">${item.quantity}</span>
                            <button onclick="changeQty(${i}, 1)" class="w-7 h-7 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-lg text-gray-700 dark:text-gray-200 flex items-center justify-center hover:bg-gray-50 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                        </div>
                        <span class="text-sm font-bold text-red-600 dark:text-red-400">Rp ${sub.toLocaleString('id-ID')}</span>
                    </div>
                </div>
            `;
        });
        html += `</div>`;
        html += `
            <div class="border-t border-gray-200 dark:border-gray-700 p-4 flex-shrink-0">
                <div class="flex justify-between text-base font-bold mb-3">
                    <span class="text-gray-900 dark:text-white">Total</span>
                    <span class="text-red-600 dark:text-red-400">Rp ${total.toLocaleString('id-ID')}</span>
                </div>
                <button onclick="proceedCheckout()" class="w-full py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-bold text-sm hover:from-red-700 hover:to-red-800 transition shadow">
                    Lanjut Checkout
                </button>
            </div>
        `;
    }

    container.innerHTML = html;
}

function proceedCheckout() {
    if (cart.length === 0) return;
    closeCart();
    openQRISModal();
}

function openQRISModal() {
    const total = cart.reduce((s, i) => s + (i.price * i.quantity), 0);
    document.getElementById('display-total').textContent = total.toLocaleString('id-ID');
    document.getElementById('checkout-modal').classList.remove('hidden');
}

function closeCheckout() {
    document.getElementById('checkout-modal').classList.add('hidden');
}

function proceedToPayment() {
    // Customer sudah bayar QRIS, tampilkan form data diri
    closeCheckout();
    document.getElementById('payment-modal').classList.remove('hidden');
    document.getElementById('customer-name').focus();
}

function backToQRIS() {
    document.getElementById('payment-modal').classList.add('hidden');
    openQRISModal();
}

function handleProofFile(e) {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        e.target.value = '';
        Swal.fire({ icon: 'error', title: 'File terlalu besar', text: 'Ukuran foto maksimal 2MB.', confirmButtonColor: '#dc2626' });
        return;
    }
    proofFile = file;
    const reader = new FileReader();
    reader.onload = function(ev) {
        proofBase64 = ev.target.result;
        document.getElementById('preview-img').src = proofBase64;
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('upload-preview').classList.remove('hidden');
        // Aktifkan tombol Sudah Bayar
        const btn = document.getElementById('sudah-bayar-btn');
        btn.disabled = false;
        btn.className = 'w-full py-3.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-sm transition flex items-center justify-center gap-2';
    };
    reader.readAsDataURL(file);
}

// ===== ORDER SUBMISSION =====
function submitOrder() {
    const name = document.getElementById('customer-name').value.trim();
    const wa = document.getElementById('customer-wa').value.trim();

    if (!name) {
        showToast('error', 'Nama tidak boleh kosong');
        document.getElementById('customer-name').focus();
        return;
    }
    if (!wa) {
        showToast('error', 'No. WhatsApp tidak boleh kosong');
        document.getElementById('customer-wa').focus();
        return;
    }

    const btn = document.getElementById('submit-payment-btn');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    fetch('/orders', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
        body: JSON.stringify({
            customer_name: name,
            no_whatsapp: wa,
            notes: document.getElementById('order-notes').value,
            proof_image: proofBase64,
            items: cart
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            currentOrder = data.order;
            cart = [];
            saveCart();
            updateCartBadge();
            localStorage.setItem('active_order', data.order.order_number);
            document.getElementById('payment-modal').classList.add('hidden');
            resetPaymentForm();
            openTracking(data.order.order_number);
        } else {
            showToast('error', data.message || 'Gagal membuat pesanan');
            btn.disabled = false;
            btn.textContent = 'Konfirmasi Pesanan';
        }
    })
    .catch(() => {
        showToast('error', 'Terjadi kesalahan');
        btn.disabled = false;
        btn.textContent = 'Konfirmasi Pesanan';
    });
}

// ===== PAYMENT =====
function resetPaymentForm() {
    proofFile = null;
    proofBase64 = null;
    document.getElementById('proof-file-input').value = '';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('upload-preview').classList.add('hidden');
    const sudahBayarBtn = document.getElementById('sudah-bayar-btn');
    sudahBayarBtn.disabled = true;
    sudahBayarBtn.className = 'w-full py-3.5 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-xl font-bold text-sm cursor-not-allowed transition flex items-center justify-center gap-2';
    document.getElementById('customer-name').value = '';
    document.getElementById('customer-wa').value = '';
    document.getElementById('order-notes').value = '';
    const btn = document.getElementById('submit-payment-btn');
    btn.disabled = false;
    btn.textContent = 'Konfirmasi Pesanan';
}

// ===== ORDER TRACKING =====
let lastTrackedStatus = null;

const statusNotif = {
    'pending_verification': { msg: 'Pesanan diterima! Kasir sedang memverifikasi...', type: 'info' },
    'processing':           { msg: 'Pembayaran disetujui! Pesananmu sedang disiapkan.', type: 'success' },
    'ready':                { msg: 'Pesananmu siap diambil!', type: 'ready' },
    'completed':            { msg: 'Pesanan selesai. Terima kasih!', type: 'success' },
    'cancelled':            { msg: 'Pesanan dibatalkan.', type: 'error' },
};

function requestNotifPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}

function sendBrowserNotif(title, body) {
    if (!('Notification' in window) || Notification.permission !== 'granted') return;
    try {
        const notif = new Notification(title, {
            body,
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            tag: 'order-status',
            renotify: true,
        });
        setTimeout(() => notif.close(), 10000);
    } catch (e) {}
}

function openTracking(orderNumber) {
    lastTrackedStatus = null;
    document.getElementById('track-order-number').textContent = orderNumber;
    document.getElementById('tracking-modal').classList.remove('hidden');
    requestNotifPermission();
    fetchStatus(orderNumber);
    trackingInterval = setInterval(() => fetchStatus(orderNumber), 5000);
}

function fetchStatus(orderNumber) {
    fetch(`/orders/${orderNumber}`)
    .then(r => r.json())
    .then(order => {
        const effectiveStatus = order.status;

        // Notifikasi saat status berubah (skip notif saat pertama kali load)
        if (lastTrackedStatus !== null && lastTrackedStatus !== effectiveStatus) {
            const notif = statusNotif[effectiveStatus];
            if (notif) {
                showTrackingNotif(notif.msg, notif.type);
                tryVibrate();
                const browserNotifs = {
                    'ready':      { title: 'Pesanan Siap Diambil!', body: 'Pesananmu sudah siap. Silakan ambil sekarang!' },
                    'cancelled':  { title: 'Pesanan Dibatalkan', body: 'Pesananmu telah dibatalkan.' },
                    'processing': { title: 'Pesanan Diproses', body: 'Pembayaran diverifikasi. Pesananmu sedang disiapkan.' },
                };
                const bn = browserNotifs[effectiveStatus];
                if (bn) sendBrowserNotif(bn.title, bn.body);
            }
        }
        lastTrackedStatus = effectiveStatus;

        updateStatusBanner(effectiveStatus);
        renderTimeline(order);
    })
    .catch(() => {});
}

function updateStatusBanner(status) {
    const banner = document.getElementById('status-banner');
    if (!banner) return;

    const bannerConfig = {
        'pending_payment':      null,
        'pending_verification': { text: 'Menunggu verifikasi kasir...', cls: 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800' },
        'processing':           { text: 'Pesananmu sedang disiapkan', cls: 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800' },
        'ready':                { text: 'Pesananmu sudah siap! Silakan ambil sekarang.', cls: 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 border border-purple-300 dark:border-purple-700 animate-pulse' },
        'completed':            { text: 'Selesai! Terima kasih telah memesan.', cls: 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800' },
        'cancelled':            { text: 'Pesanan dibatalkan.', cls: 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800' },
    };

    const cfg = bannerConfig[status];
    if (!cfg) {
        banner.classList.add('hidden');
        return;
    }
    banner.textContent = cfg.text;
    banner.innerHTML = cfg.text;
    banner.className = `mb-4 px-4 py-3 rounded-xl text-sm font-semibold text-center ${cfg.cls}`;
    banner.classList.remove('hidden');
}

function showTrackingNotif(message, type) {
    const colors = {
        success: 'bg-green-500',
        error:   'bg-red-500',
        warning: 'bg-yellow-500',
        info:    'bg-blue-500',
        ready:   'bg-purple-600',
    };
    const toast = document.getElementById('toast');
    toast.className = `fixed top-4 right-4 z-50 px-5 py-4 rounded-xl shadow-xl text-white text-sm font-semibold max-w-xs ${colors[type] || 'bg-gray-700'}`;
    toast.innerHTML = `<span>${message}</span>`;
    toast.classList.remove('hidden');
    // Notif status penting tampil lebih lama
    const duration = (type === 'ready' || type === 'warning') ? 6000 : 4000;
    setTimeout(() => toast.classList.add('hidden'), duration);
}

function tryVibrate() {
    if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
}

function renderTimeline(order) {
    // Handle cancelled
    if (order.status === 'cancelled') {
        document.getElementById('status-timeline').innerHTML = `
            <div class="text-center py-2">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-base font-bold text-red-600 dark:text-red-400 mb-1">Pesanan Dibatalkan</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Silakan hubungi kasir jika ada pertanyaan.</p>
            </div>
        `;
        clearInterval(trackingInterval);
        localStorage.removeItem('active_order');
        return;
    }

    // Hapus localStorage jika sudah completed
    if (order.status === 'completed') {
        localStorage.removeItem('active_order');
    }

    const steps = [
        { key: 'pending_payment',      label: 'Pesanan Dibuat',     desc: 'Pesanan berhasil diterima' },
        { key: 'pending_verification', label: 'Verifikasi Bayar',   desc: 'Kasir sedang memverifikasi' },
        { key: 'processing',           label: 'Sedang Diproses',    desc: 'Pesanan sedang disiapkan' },
        { key: 'ready',                label: 'Siap Diambil',       desc: 'Silakan ambil pesananmu' },
        { key: 'completed',            label: 'Selesai',            desc: 'Selamat menikmati!' },
    ];

    const currentIdx = steps.findIndex(s => s.key === order.status);
    let html = '';

    const isCompleted = order.status === 'completed';

    steps.forEach((step, i) => {
        const done = i < currentIdx || (isCompleted && i === currentIdx);
        const active = i === currentIdx && !isCompleted;
        const pending = i > currentIdx;

        const dotClass = done
            ? 'bg-green-500'
            : active
            ? 'bg-yellow-400 animate-pulse'
            : 'bg-gray-300 dark:bg-gray-600';

        const titleClass = pending ? 'text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-white';
        const descClass = pending ? 'text-gray-300 dark:text-gray-600' : 'text-gray-500 dark:text-gray-400';

        html += `
            <div class="flex items-start gap-3">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full ${dotClass} flex items-center justify-center flex-shrink-0">
                        ${done ? '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' : ''}
                        ${active ? '<div class="w-3 h-3 bg-white rounded-full"></div>' : ''}
                    </div>
                    ${i < steps.length - 1 ? `<div class="w-0.5 h-6 ${done ? 'bg-green-300' : 'bg-gray-200 dark:bg-gray-700'} mt-1"></div>` : ''}
                </div>
                <div class="pb-4">
                    <p class="text-sm font-semibold ${titleClass}">${step.label}</p>
                    <p class="text-xs ${descClass}">${step.desc}</p>
                    ${active ? '<p class="text-xs text-yellow-500 font-medium mt-0.5">Sedang berlangsung...</p>' : ''}
                </div>
            </div>
        `;
    });

    document.getElementById('status-timeline').innerHTML = html;
}

function newOrder() {
    clearInterval(trackingInterval);
    lastTrackedStatus = null;
    currentOrder = null;
    localStorage.removeItem('active_order');
    document.getElementById('tracking-modal').classList.add('hidden');
    document.getElementById('status-banner').classList.add('hidden');
}


// ===== UTILS =====
function showToast(type, message) {
    const colors = { success: 'bg-green-500', error: 'bg-red-500', warning: 'bg-yellow-500' };
    const toast = document.getElementById('toast');
    toast.className = `fixed top-4 right-4 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium ${colors[type]}`;
    toast.textContent = message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 2500);
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}
</script>
@endpush
