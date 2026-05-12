@extends('layouts.app')

@section('title', 'Order Walk-in - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center shadow">
                        <span class="text-lg font-bold text-white">F&B</span>
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-gray-900 dark:text-white">Order Walk-in</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Input pesanan langsung / bayar tunai</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('kasir.dashboard') }}"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                        &larr; Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-sm text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('kasir.walkin.store') }}" id="walkinForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left: Menu Selection -->
                <div class="lg:col-span-2 space-y-4">

                    <!-- Search -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
                        <input type="text" id="menuSearch" placeholder="Cari menu..."
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>

                    <!-- Menu per Kategori -->
                    @foreach($categories as $cat)
                    @if($cat->menus->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm" data-category="{{ strtolower($cat->name) }}">
                        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ $cat->name }}</h3>
                        </div>
                        <div class="p-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($cat->menus as $menu)
                            <button type="button" onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})"
                                class="menu-item flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 transition text-left"
                                data-name="{{ strtolower($menu->name) }}">
                                @if($menu->image_base64)
                                <img src="{{ $menu->image_base64 }}" alt="{{ $menu->name }}"
                                    class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border border-gray-200 dark:border-gray-600">
                                @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $menu->name }}</p>
                                    <p class="text-xs text-red-600 dark:text-red-400 font-semibold">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                </div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endforeach

                </div>

                <!-- Right: Order Summary -->
                <div class="space-y-4">

                    <!-- Customer Info -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">Info Pelanggan</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Pelanggan <span class="text-red-500">*</span></label>
                                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                                    placeholder="Nama pelanggan..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent @error('customer_name') border-red-500 @enderror">
                                @error('customer_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Catatan</label>
                                <textarea name="notes" rows="2" placeholder="Catatan pesanan..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Cart -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4">
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-3">Pesanan</h3>

                        <div id="cartEmpty" class="py-6 text-center text-gray-400 text-sm">
                            Belum ada item dipilih.<br>Klik menu untuk menambahkan.
                        </div>

                        <div id="cartItems" class="space-y-2 hidden"></div>
                        <div id="cartInputs"></div>

                        <div id="cartTotal" class="hidden mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Total</span>
                                <span id="totalDisplay" class="text-lg font-bold text-red-600 dark:text-red-400">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                        <p class="text-xs font-semibold text-amber-700 dark:text-amber-400 mb-1">Metode Pembayaran</p>
                        <p class="text-sm text-amber-800 dark:text-amber-300 font-bold">Tunai / Walk-in</p>
                        <p class="text-xs text-amber-600 dark:text-amber-500 mt-1">Order langsung masuk ke antrian diproses. Invoice akan dicetak setelah konfirmasi.</p>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="submitBtn" disabled
                        class="w-full py-3 bg-red-600 hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed text-white rounded-xl text-sm font-bold transition shadow">
                        Konfirmasi & Buat Invoice
                    </button>

                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const cart = {};

function addToCart(id, name, price) {
    if (cart[id]) {
        cart[id].qty++;
    } else {
        cart[id] = { name, price, qty: 1 };
    }
    renderCart();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}

function renderCart() {
    const ids = Object.keys(cart);
    const cartItems = document.getElementById('cartItems');
    const cartEmpty = document.getElementById('cartEmpty');
    const cartTotal = document.getElementById('cartTotal');
    const cartInputs = document.getElementById('cartInputs');
    const submitBtn = document.getElementById('submitBtn');
    const totalDisplay = document.getElementById('totalDisplay');

    if (ids.length === 0) {
        cartItems.classList.add('hidden');
        cartEmpty.classList.remove('hidden');
        cartTotal.classList.add('hidden');
        submitBtn.disabled = true;
        cartInputs.innerHTML = '';
        return;
    }

    cartEmpty.classList.add('hidden');
    cartItems.classList.remove('hidden');
    cartTotal.classList.remove('hidden');
    submitBtn.disabled = false;

    let total = 0;
    let itemsHtml = '';
    let inputsHtml = '';
    let idx = 0;

    for (const id of ids) {
        const item = cart[id];
        const subtotal = item.price * item.qty;
        total += subtotal;

        itemsHtml += `
            <div class="flex items-center justify-between gap-2 py-1.5">
                <span class="text-sm text-gray-700 dark:text-gray-300 flex-1 truncate">${item.name}</span>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                    <button type="button" onclick="changeQty(${id}, -1)"
                        class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-gray-300 transition flex items-center justify-center">-</button>
                    <span class="text-sm font-bold text-gray-900 dark:text-white w-5 text-center">${item.qty}</span>
                    <button type="button" onclick="changeQty(${id}, 1)"
                        class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-gray-300 transition flex items-center justify-center">+</button>
                </div>
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 w-20 text-right">Rp ${subtotal.toLocaleString('id-ID')}</span>
            </div>`;

        inputsHtml += `
            <input type="hidden" name="items[${idx}][menu_id]" value="${id}">
            <input type="hidden" name="items[${idx}][quantity]" value="${item.qty}">`;
        idx++;
    }

    cartItems.innerHTML = itemsHtml;
    cartInputs.innerHTML = inputsHtml;
    totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Search filter
document.getElementById('menuSearch').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.menu-item').forEach(btn => {
        const name = btn.dataset.name;
        btn.closest('.menu-item').style.display = (!q || name.includes(q)) ? '' : 'none';
    });
});
</script>
@endpush
@endsection
