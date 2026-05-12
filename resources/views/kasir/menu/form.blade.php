@extends('layouts.app')

@section('title', isset($menu) ? 'Edit Menu' : 'Tambah Menu')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">

    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center shadow">
                        <span class="text-lg font-bold text-white">pos</span>
                    </div>
                    <div>
                        <h1 class="text-base font-bold text-gray-900 dark:text-white">
                            {{ isset($menu) ? 'Edit Menu' : 'Tambah Menu Baru' }}
                        </h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Manajemen Menu</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ route('kasir.menu.index') }}"
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                        &larr; Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

            <form method="POST"
                action="{{ isset($menu) ? route('kasir.menu.update', $menu) : route('kasir.menu.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if(isset($menu)) @method('PUT') @endif

                @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', isset($menu) ? $menu->category_id : '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Menu -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                        value="{{ old('name', isset($menu) ? $menu->name : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Contoh: Americano">
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Deskripsi singkat menu...">{{ old('description', isset($menu) ? $menu->description : '') }}</textarea>
                </div>

                <!-- Harga & Stok -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" required min="0"
                            value="{{ old('price', isset($menu) ? $menu->price : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="25000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" required min="0"
                            value="{{ old('stock', isset($menu) ? $menu->stock : 50) }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Foto Menu -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Foto Menu</label>

                    @if(isset($menu) && $menu->image_base64)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ $menu->image_base64 }}" alt="{{ $menu->name }}"
                            class="w-20 h-20 rounded-xl object-cover border border-gray-200 dark:border-gray-600">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Foto saat ini</p>
                            <label class="flex items-center gap-2 text-xs text-red-600 dark:text-red-400 cursor-pointer">
                                <input type="checkbox" name="remove_image" value="1" class="rounded">
                                Hapus foto ini
                            </label>
                        </div>
                    </div>
                    @endif

                    <div id="drop-zone"
                        class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center cursor-pointer hover:border-red-400 transition"
                        onclick="document.getElementById('image-input').click()">
                        <input type="file" id="image-input" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                        <div id="img-placeholder">
                            <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ isset($menu) && $menu->image_base64 ? 'Klik untuk ganti foto' : 'Klik untuk upload foto' }}</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG maks 2MB</p>
                        </div>
                        <div id="img-preview" class="hidden">
                            <img id="preview-img" class="max-h-40 mx-auto rounded-xl shadow mb-2">
                            <p class="text-xs text-green-600 dark:text-green-400">Foto siap diupload</p>
                        </div>
                    </div>
                </div>

                <!-- Tersedia -->
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_available" value="1"
                            {{ old('is_available', isset($menu) ? $menu->is_available : true) ? 'checked' : '' }}
                            class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Menu tersedia untuk dipesan</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('kasir.menu.index') }}"
                        class="flex-1 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium text-center hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold transition shadow">
                        {{ isset($menu) ? 'Simpan Perubahan' : 'Tambah Menu' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(e) {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        Swal.fire({ icon: 'warning', title: 'File terlalu besar', text: 'Ukuran foto maksimal 2MB.', confirmButtonColor: '#dc2626' });
        return;
    }
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('preview-img').src = ev.target.result;
        document.getElementById('img-placeholder').classList.add('hidden');
        document.getElementById('img-preview').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
