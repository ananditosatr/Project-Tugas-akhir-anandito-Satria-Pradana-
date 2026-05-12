@extends('layouts.app')

@section('title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')

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
                        <h1 class="text-base font-bold text-gray-900 dark:text-white">
                            {{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
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

    <div class="max-w-md mx-auto px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">

            <form method="POST"
                action="{{ isset($category) ? route('kasir.category.update', $category) : route('kasir.category.store') }}">
                @csrf
                @if(isset($category)) @method('PUT') @endif

                @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Nama Kategori -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                        value="{{ old('name', isset($category) ? $category->name : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Contoh: Coffee Milk">
                </div>

                <!-- Urutan Tampil -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Urutan Tampil <span class="text-red-500">*</span></label>
                    <input type="number" name="display_order" required min="0"
                        value="{{ old('display_order', isset($category) ? $category->display_order : 10) }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <p class="text-xs text-gray-400 mt-1">Angka kecil tampil lebih dulu</p>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <option value="active" {{ old('status', isset($category) ? $category->status : 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', isset($category) ? $category->status : '') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('kasir.menu.index') }}"
                        class="flex-1 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium text-center hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold transition shadow">
                        {{ isset($category) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
