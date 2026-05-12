@extends('layouts.app')

@section('title', 'Manajemen Menu - ' . config('app.name'))

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
                        <h1 class="text-base font-bold text-gray-900 dark:text-white">Manajemen Menu</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kelola menu & kategori</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('kasir.history') }}"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                        History
                    </a>
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

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-sm text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
        @endif

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Menu</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Total {{ $menus->count() }} menu &bull; {{ $categories->count() }} kategori</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kasir.category.create') }}"
                    class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    + Kategori Baru
                </a>
                <a href="{{ route('kasir.menu.create') }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold transition shadow">
                    + Tambah Menu
                </a>
            </div>
        </div>

        <!-- Categories Overview -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
            @foreach($categories as $cat)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-3 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $cat->name }}</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white mt-0.5">{{ $cat->menus->count() }}</p>
                        <p class="text-xs text-gray-400">menu</p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('kasir.category.edit', $cat) }}"
                            class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                        @if($cat->menus->count() === 0)
                        <form method="POST" action="{{ route('kasir.category.destroy', $cat) }}" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete(this, 'Hapus kategori &ldquo;{{ $cat->name }}&rdquo;?')" class="text-xs text-red-500 hover:underline">Hapus</button>
                        </form>
                        @endif
                    </div>
                </div>
                <span class="inline-block mt-2 px-1.5 py-0.5 rounded text-xs {{ $cat->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                    {{ $cat->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            @endforeach
        </div>

        <!-- Menu Table per Category -->
        @foreach($categories as $cat)
        @php $catMenus = $menus->where('category_id', $cat->id); @endphp
        @if($catMenus->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm mb-4">
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $cat->name }}</h3>
                    <span class="text-xs text-gray-400">({{ $catMenus->count() }} item)</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            <th class="text-left px-5 py-3 font-medium">Nama Menu</th>
                            <th class="text-left px-3 py-3 font-medium">Deskripsi</th>
                            <th class="text-right px-3 py-3 font-medium">Harga</th>
                            <th class="text-center px-3 py-3 font-medium">Stok</th>
                            <th class="text-center px-3 py-3 font-medium">Tersedia</th>
                            <th class="text-center px-5 py-3 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($catMenus as $menu)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    @if($menu->image_base64)
                                    <img src="{{ $menu->image_base64 }}" alt="{{ $menu->name }}"
                                        class="w-10 h-10 rounded-lg object-cover flex-shrink-0 border border-gray-200 dark:border-gray-600">
                                    @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    @endif
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $menu->name }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-gray-500 dark:text-gray-400 max-w-xs">
                                <span class="line-clamp-2">{{ $menu->description ?: '-' }}</span>
                            </td>
                            <td class="px-3 py-3 text-right font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                Rp {{ number_format($menu->price, 0, ',', '.') }}
                            </td>
                            <td class="px-3 py-3 text-center">
                                <span class="font-medium {{ $menu->stock <= 5 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $menu->stock }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-center">
                                <button onclick="toggleMenu({{ $menu->id }}, this)"
                                    data-available="{{ $menu->is_available ? '1' : '0' }}"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none
                                        {{ $menu->is_available ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                                        {{ $menu->is_available ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kasir.menu.edit', $menu) }}"
                                        class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('kasir.menu.destroy', $menu) }}" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this, 'Hapus menu &ldquo;{{ $menu->name }}&rdquo;?')"
                                            class="px-3 py-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @endforeach

        @if($menus->count() === 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center">
            <p class="text-gray-400 dark:text-gray-500 mb-4">Belum ada menu. Tambahkan menu pertama!</p>
            <a href="{{ route('kasir.menu.create') }}"
                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-bold transition">
                + Tambah Menu
            </a>
        </div>
        @endif

    </div>
</div>

<div id="toast" class="hidden fixed top-4 right-4 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium"></div>
@endsection

@push('scripts')
<script>
function toggleMenu(menuId, btn) {
    const isAvailable = btn.dataset.available === '1';

    fetch(`/kasir/menu/${menuId}/toggle`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            btn.dataset.available = data.is_available ? '1' : '0';
            if (data.is_available) {
                btn.classList.remove('bg-gray-300', 'dark:bg-gray-600');
                btn.classList.add('bg-green-500');
                btn.querySelector('span').classList.remove('translate-x-1');
                btn.querySelector('span').classList.add('translate-x-6');
            } else {
                btn.classList.remove('bg-green-500');
                btn.classList.add('bg-gray-300');
                btn.querySelector('span').classList.remove('translate-x-6');
                btn.querySelector('span').classList.add('translate-x-1');
            }
            showToast('success', data.is_available ? 'Menu diaktifkan' : 'Menu dinonaktifkan');
        }
    });
}

function showToast(type, message) {
    const colors = { success: 'bg-green-500', error: 'bg-red-500' };
    const toast = document.getElementById('toast');
    toast.className = `fixed top-4 right-4 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium ${colors[type]}`;
    toast.textContent = message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 2500);
}

function confirmDelete(btn, message) {
    const form = btn.closest('form');
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
    }).then(result => {
        if (result.isConfirmed) form.submit();
    });
}
</script>
@endpush
