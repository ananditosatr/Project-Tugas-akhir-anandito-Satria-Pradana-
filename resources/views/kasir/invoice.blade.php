@extends('layouts.app')

@section('title', 'Invoice {{ $order->order_number }} - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8 px-4">

    <!-- Action Bar (hidden on print) -->
    <div class="no-print max-w-lg mx-auto mb-4 flex items-center justify-between">
        <a href="{{ route('kasir.dashboard') }}"
            class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
            &larr; Dashboard
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()"
                class="px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-lg text-sm font-medium hover:bg-gray-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Invoice
            </button>
            @if($order->payment_method === 'cash')
            <a href="{{ route('kasir.walkin') }}"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                + Order Baru
            </a>
            @endif
        </div>
    </div>

    <!-- Invoice Card -->
    <div class="max-w-lg mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden invoice-card">

        <!-- Header -->
        <div class="bg-gradient-to-br from-red-600 to-red-700 px-6 py-6 text-white text-center">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <span class="text-xl font-bold">F&B</span>
            </div>
            <h1 class="text-xl font-bold">F&B POS Critasena</h1>
            <p class="text-red-100 text-sm mt-0.5">INVOICE PESANAN</p>
        </div>

        <!-- Order Info -->
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">No. Order</p>
                    <p class="text-lg font-mono font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    @php
                        $statusMap = [
                            'pending_payment'      => ['label' => 'Belum Bayar',   'class' => 'bg-gray-100 text-gray-600'],
                            'pending_verification' => ['label' => 'Verifikasi',     'class' => 'bg-yellow-100 text-yellow-700'],
                            'processing'           => ['label' => 'Diproses',       'class' => 'bg-blue-100 text-blue-700'],
                            'ready'                => ['label' => 'Siap Diambil',   'class' => 'bg-purple-100 text-purple-700'],
                            'completed'            => ['label' => 'Selesai',         'class' => 'bg-green-100 text-green-700'],
                            'cancelled'            => ['label' => 'Dibatalkan',     'class' => 'bg-red-100 text-red-700'],
                        ];
                        $sc = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $sc['class'] }}">
                        {{ $sc['label'] }}
                    </span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-xs text-gray-400">Pelanggan</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $order->customer_name }}</p>
                    @if($order->no_whatsapp)
                    <p class="text-xs text-gray-500 dark:text-gray-400">WA: {{ $order->no_whatsapp }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-gray-400">Waktu Order</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Metode Bayar</p>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        @if($order->payment_method === 'cash')
                            Tunai
                        @elseif($order->payment_method === 'qris')
                            QRIS
                        @else
                            {{ $order->payment_method === 'walkin' ? 'Tunai / Walk-in' : 'Transfer Bank' }}
                        @endif
                    </p>
                </div>
                @if($order->notes)
                <div>
                    <p class="text-xs text-gray-400">Catatan</p>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Items -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Detail Pesanan</p>
            <div class="space-y-2">
                @foreach($order->items as $item)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $item->menu->name ?? 'Menu dihapus' }}</p>
                        <p class="text-xs text-gray-400">{{ $item->quantity }}x &times; Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Total -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-700 dark:text-gray-300">TOTAL BAYAR</span>
                <span class="text-2xl font-bold text-red-600 dark:text-red-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
            @if($order->payment_method === 'cash')
            <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-medium">Lunas - Bayar Tunai</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="px-6 py-5 text-center">
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">
                Tunjukkan invoice ini saat mengambil pesanan
            </p>

            <!-- Order Number QR-style barcode visual -->
            <div class="inline-block border-2 border-gray-200 dark:border-gray-600 rounded-xl px-6 py-3 bg-gray-50 dark:bg-gray-700/30">
                <p class="text-xs text-gray-400 mb-1">Kode Pesanan</p>
                <p class="font-mono text-xl font-bold tracking-widest text-gray-900 dark:text-white">{{ $order->order_number }}</p>
            </div>

            <p class="text-xs text-gray-400 dark:text-gray-500 mt-4">
                Terima kasih telah berkunjung &bull; F&B POS Critasena
            </p>
        </div>

    </div>

    @if(session('success'))
    <div class="no-print max-w-lg mx-auto mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-400 text-center">
        {{ session('success') }}
    </div>
    @endif

</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { background: white !important; }
    .invoice-card {
        box-shadow: none !important;
        border-radius: 0 !important;
        max-width: 100% !important;
    }
}
</style>
@endsection
