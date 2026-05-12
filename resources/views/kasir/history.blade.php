@extends('layouts.app')

@section('title', 'History Transaksi - ' . config('app.name'))

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
                        <h1 class="text-base font-bold text-gray-900 dark:text-white">History Transaksi</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Riwayat semua pesanan</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('kasir.dashboard') }}"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                        &larr; Dashboard
                    </a>
                    <form method="POST" action="{{ route('kasir.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-gray-400">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Order</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-green-500">
                <p class="text-xs text-gray-500 dark:text-gray-400">Selesai</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-0.5">{{ $stats['completed'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-blue-500">
                <p class="text-xs text-gray-500 dark:text-gray-400">Diproses</p>
                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-0.5">{{ $stats['processing'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border-l-4 border-red-500">
                <p class="text-xs text-gray-500 dark:text-gray-400">Dibatalkan</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-0.5">{{ $stats['cancelled'] }}</p>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-xl p-5 mb-6 shadow">
            <p class="text-red-100 text-sm font-medium mb-1">Total Pendapatan (Order Selesai)</p>
            <p class="text-3xl font-bold text-white">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-red-200 text-xs mt-1">
                {{ request('date') ? 'Tanggal: ' . \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Hari ini: ' . now()->format('d M Y') }}
            </p>
        </div>

        <!-- Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-4">
            <form method="GET" action="{{ route('kasir.history') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal</label>
                    <input type="date" name="date"
                        value="{{ request('date', today()->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="pending_payment"      {{ request('status') === 'pending_payment'      ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="pending_verification" {{ request('status') === 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="processing"           {{ request('status') === 'processing'           ? 'selected' : '' }}>Diproses</option>
                        <option value="ready"                {{ request('status') === 'ready'                ? 'selected' : '' }}>Siap Diambil</option>
                        <option value="completed"            {{ request('status') === 'completed'            ? 'selected' : '' }}>Selesai / Lunas</option>
                        <option value="cancelled"            {{ request('status') === 'cancelled'            ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                        Filter
                    </button>
                    <a href="{{ route('kasir.history') }}"
                        class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $orders->total() }} transaksi ditemukan
                </p>
            </div>

            @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                            <th class="text-left px-5 py-3 font-medium">Order ID</th>
                            <th class="text-left px-3 py-3 font-medium">Customer</th>
                            <th class="text-left px-3 py-3 font-medium">Item</th>
                            <th class="text-right px-3 py-3 font-medium">Total</th>
                            <th class="text-center px-3 py-3 font-medium">Status</th>
                            <th class="text-center px-3 py-3 font-medium">Pembayaran</th>
                            <th class="text-right px-3 py-3 font-medium">Waktu</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition cursor-pointer"
                            onclick="toggleDetail({{ $order->id }})">
                            <td class="px-5 py-3">
                                <span class="font-mono text-xs font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                            </td>
                            <td class="px-3 py-3 text-gray-700 dark:text-gray-300">{{ $order->customer_name }}</td>
                            <td class="px-3 py-3 text-gray-500 dark:text-gray-400">{{ $order->items->count() }} item</td>
                            <td class="px-3 py-3 text-right font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-center">
                                @php
                                    $statusConfig = [
                                        'pending_payment'      => ['label' => 'Belum Bayar',   'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'],
                                        'pending_verification' => ['label' => 'Verifikasi',     'class' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'],
                                        'processing'           => ['label' => 'Diproses',       'class' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'],
                                        'ready'                => ['label' => 'Siap Ambil',     'class' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400'],
                                        'completed'            => ['label' => 'Selesai',         'class' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400'],
                                        'cancelled'            => ['label' => 'Dibatalkan',     'class' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'],
                                    ];
                                    $cfg = $statusConfig[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600'];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $cfg['class'] }}">
                                    {{ $cfg['label'] }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($order->status === 'completed' || $order->status === 'processing' || $order->status === 'ready')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                        Lunas
                                    </span>
                                @elseif($order->status === 'cancelled')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                        Batal
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-right text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                {{ $order->created_at->format('H:i') }}
                            </td>
                            <td class="px-5 py-3" onclick="event.stopPropagation()">
                                <a href="{{ route('kasir.invoice', $order->order_number) }}"
                                    class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition whitespace-nowrap">
                                    Invoice
                                </a>
                            </td>
                        </tr>
                        <!-- Detail Row (hidden) -->
                        <tr id="detail-{{ $order->id }}" class="hidden bg-gray-50 dark:bg-gray-700/20">
                            <td colspan="8" class="px-5 py-4">
                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                    <p class="font-semibold text-gray-800 dark:text-gray-200 mb-2">Detail Pesanan:</p>
                                    <ul class="space-y-1 mb-3">
                                        @foreach($order->items as $item)
                                        <li class="flex justify-between max-w-sm">
                                            <span>{{ $item->quantity }}x {{ $item->menu->name ?? 'Menu dihapus' }}</span>
                                            <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @if($order->notes)
                                    <p class="italic text-gray-400">"{{ $order->notes }}"</p>
                                    @endif
                                    <p class="text-gray-400 mt-2">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
            @endif

            @else
            <div class="py-12 text-center">
                <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada transaksi ditemukan</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDetail(orderId) {
    const row = document.getElementById('detail-' + orderId);
    row.classList.toggle('hidden');
}
</script>
@endpush
