@extends('layouts.app')

@section('title', 'Kasir Dashboard - F&B POS Critasena')

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
                        <h1 class="text-base font-bold text-gray-900 dark:text-white">Kasir Dashboard</h1>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span id="live-dot" class="inline-block w-2 h-2 rounded-full bg-green-500 transition-colors duration-300"></span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Live &bull; refresh 3 detik</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('kasir.walkin') }}"
                        class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold transition shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Walk-in
                    </a>
                    <a href="{{ route('kasir.history') }}"
                        class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        History
                    </a>
                    <a href="{{ route('kasir.menu.index') }}"
                        class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Kelola Menu
                    </a>
                    <a href="{{ route('customer.order') }}" target="_blank"
                        class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg text-sm font-medium hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Preview Menu
                    </a>
                    <button onclick="toggleDarkMode()" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                        <svg id="icon-moon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="icon-sun" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path>
                        </svg>
                    </button>

                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->username }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kasir</p>
                    </div>

                    <form method="POST" action="{{ route('kasir.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border-l-4 border-red-500">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pending Review</p>
                <h3 id="stat-pending" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $pendingReview->count() }}</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Processing</p>
                <h3 id="stat-processing" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $processingOrders->count() }}</h3>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Ready Pickup</p>
                <h3 id="stat-ready" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $readyOrders->count() }}</h3>
            </div>
        </div>

        <!-- Pending Review Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-6">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">Antrian Review Pembayaran</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Auto-refresh setiap 3 detik</p>
                </div>
            </div>
            <div id="pending-review-list" class="p-5 space-y-4">
                @forelse($pendingReview as $order)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                                <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-medium">Pending</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $order->customer_name }}</p>
                            @if($order->no_whatsapp)
                            <p class="text-xs text-green-600 dark:text-green-400">WA: {{ $order->no_whatsapp }}</p>
                            @endif
                            <p class="text-sm font-bold text-red-600 dark:text-red-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $order->items->count() }} item &bull; {{ $order->created_at->diffForHumans() }}</p>
                        </div>

                        </div>

                    @if($order->notes)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 italic">"{{ $order->notes }}"</p>
                    @endif

                    <div class="flex gap-2">
                        <button onclick="approvePayment({{ $order->id }})"
                            class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                            Approve
                        </button>
                        <button onclick="rejectOrder({{ $order->id }})"
                            class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                            Tolak
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-400 dark:text-gray-500 py-8 text-sm">Tidak ada order yang perlu direview</p>
                @endforelse
            </div>
        </div>

        <!-- Order Status Management -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Manajemen Status Order</h2>
            </div>
            <div id="order-management-list" class="p-5 space-y-3">
                @forelse($processingOrders as $order)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ $order->order_number }}</span>
                            <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs">Diproses</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->customer_name }}@if($order->no_whatsapp) &bull; WA: {{ $order->no_whatsapp }}@endif &bull; Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <button onclick="updateStatus({{ $order->id }}, 'ready')"
                        class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                        Siap
                    </button>
                </div>
                @empty
                <p class="text-center text-gray-400 text-sm py-4">Tidak ada order sedang diproses</p>
                @endforelse

                @foreach($readyOrders as $order)
                <div class="border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center justify-between bg-green-50 dark:bg-green-900/10">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ $order->order_number }}</span>
                            <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs">Siap Diambil</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->customer_name }}@if($order->no_whatsapp) &bull; WA: {{ $order->no_whatsapp }}@endif &bull; Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <button onclick="updateStatus({{ $order->id }}, 'completed')"
                        class="px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition">
                        Selesai
                    </button>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<!-- Proof Image Modal -->
<div id="proof-modal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" onclick="closeProofModal()">
    <div class="bg-white dark:bg-gray-800 rounded-xl max-w-lg w-full p-5" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-900 dark:text-white">Bukti Pembayaran</h3>
            <button onclick="closeProofModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <img id="proof-img" src="" alt="Bukti Bayar" class="w-full rounded-lg">
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="hidden fixed top-4 right-4 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium"></div>
@endsection

@push('scripts')
<script>
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
});

function viewProof(imageBase64) {
    document.getElementById('proof-img').src = imageBase64;
    document.getElementById('proof-modal').classList.remove('hidden');
}

function closeProofModal() {
    document.getElementById('proof-modal').classList.add('hidden');
}

function approvePayment(orderId) {
    Swal.fire({
        title: 'Approve Pembayaran?',
        text: 'Pembayaran akan disetujui dan pesanan diproses.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Approve',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`/kasir/orders/${orderId}/approve`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast('error', data.message);
            }
        });
    });
}

function rejectOrder(orderId) {
    Swal.fire({
        title: 'Tolak Pesanan?',
        text: 'Pesanan akan dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tolak',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`/kasir/orders/${orderId}/reject`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body: JSON.stringify({ reason: 'Ditolak kasir', allow_retry: false })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast('error', data.message);
            }
        });
    });
}

function updateStatus(orderId, newStatus) {
    const label = newStatus === 'ready' ? 'Siap Diambil' : 'Selesai';
    Swal.fire({
        title: `Update ke "${label}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Update',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`/kasir/orders/${orderId}/update-status`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
            body: JSON.stringify({ status: newStatus })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('success', data.message);
                setTimeout(() => location.reload(), 1200);
            }
        });
    });
}

function showToast(type, message) {
    const colors = { success: 'bg-green-500', error: 'bg-red-500', warning: 'bg-yellow-500' };
    const toast = document.getElementById('toast');
    toast.className = `fixed top-4 right-4 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium ${colors[type]}`;
    toast.textContent = message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

// ── Live Polling ──────────────────────────────────────────────
const POLL_URL = '{{ route("kasir.dashboard.poll") }}';
let lastPendingCount = {{ $pendingReview->count() }};

function formatRupiah(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

function renderPendingReview(orders) {
    const wrap = document.getElementById('pending-review-list');
    const badge = document.getElementById('stat-pending');
    if (badge) badge.textContent = orders.length;

    if (orders.length === 0) {
        wrap.innerHTML = '<p class="text-center text-gray-400 dark:text-gray-500 py-8 text-sm">Tidak ada order yang perlu direview</p>';
        return;
    }

    wrap.innerHTML = orders.map(o => `
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-bold text-gray-900 dark:text-white">${o.order_number}</span>
                        <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs font-medium">Pending</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">${o.customer_name}</p>
                    ${o.no_whatsapp ? `<p class="text-xs text-green-600 dark:text-green-400">WA: ${o.no_whatsapp}</p>` : ''}
                    <p class="text-sm font-bold text-red-600 dark:text-red-400">${formatRupiah(o.total_amount)}</p>
                    <p class="text-xs text-gray-400 mt-1">${o.items_count} item &bull; ${o.created_at}</p>
                    ${o.notes ? `<p class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">"${o.notes}"</p>` : ''}
                </div>
                ${o.proof_image
                    ? `<div class="ml-3 flex-shrink-0">
                        <div class="w-20 h-20 rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-600 cursor-pointer hover:scale-105 transition"
                            onclick="viewProof('${o.proof_image}')">
                            <img src="${o.proof_image}" alt="Bukti Bayar" class="w-full h-full object-cover">
                        </div>
                       </div>`
                    : `<div class="ml-3 w-20 h-20 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-xs text-gray-400 text-center px-1">Belum upload</span>
                       </div>`
                }
            </div>
            <div class="flex gap-2">
                <button onclick="approvePayment(${o.id})"
                    class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                    Approve
                </button>
                <button onclick="rejectOrder(${o.id})"
                    class="flex-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                    Tolak
                </button>
            </div>
        </div>
    `).join('');
}

function renderOrderManagement(processing, ready) {
    const wrap = document.getElementById('order-management-list');
    const statProcessing = document.getElementById('stat-processing');
    const statReady = document.getElementById('stat-ready');
    if (statProcessing) statProcessing.textContent = processing.length;
    if (statReady) statReady.textContent = ready.length;

    let html = '';

    if (processing.length === 0 && ready.length === 0) {
        html = '<p class="text-center text-gray-400 text-sm py-4">Tidak ada order aktif</p>';
    }

    html += processing.map(o => `
        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-semibold text-gray-900 dark:text-white text-sm">${o.order_number}</span>
                    <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs">Diproses</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">${o.customer_name}${o.no_whatsapp ? ` &bull; WA: ${o.no_whatsapp}` : ''} &bull; ${formatRupiah(o.total_amount)}</p>
            </div>
            <button onclick="updateStatus(${o.id}, 'ready')"
                class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                Siap
            </button>
        </div>
    `).join('');

    html += ready.map(o => `
        <div class="border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center justify-between bg-green-50 dark:bg-green-900/10">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-semibold text-gray-900 dark:text-white text-sm">${o.order_number}</span>
                    <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs">Siap Diambil</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">${o.customer_name}${o.no_whatsapp ? ` &bull; WA: ${o.no_whatsapp}` : ''} &bull; ${formatRupiah(o.total_amount)}</p>
            </div>
            <button onclick="updateStatus(${o.id}, 'completed')"
                class="px-3 py-1.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition">
                Selesai
            </button>
        </div>
    `).join('');

    wrap.innerHTML = html;
}

function pollDashboard() {
    fetch(POLL_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            // Notif jika ada order baru masuk
            if (data.pending_review.length > lastPendingCount) {
                showToast('success', `${data.pending_review.length - lastPendingCount} order baru menunggu verifikasi!`);
            }
            lastPendingCount = data.pending_review.length;

            renderPendingReview(data.pending_review);
            renderOrderManagement(data.processing_orders, data.ready_orders);

            // Update live dot
            const dot = document.getElementById('live-dot');
            if (dot) {
                dot.classList.remove('bg-gray-400');
                dot.classList.add('bg-green-500');
                setTimeout(() => {
                    dot.classList.remove('bg-green-500');
                    dot.classList.add('bg-gray-400');
                }, 500);
            }
        })
        .catch(() => {
            const dot = document.getElementById('live-dot');
            if (dot) dot.classList.add('bg-red-500');
        });
}

setInterval(pollDashboard, 3000);
</script>
@endpush
