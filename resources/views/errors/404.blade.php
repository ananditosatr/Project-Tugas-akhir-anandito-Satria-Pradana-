@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center px-4">
    <div class="text-center">
        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-red-600 to-red-700 rounded-2xl flex items-center justify-center shadow-lg mb-6">
            <span class="text-3xl font-bold text-white">F&B</span>
        </div>

        <h1 class="text-8xl font-bold text-red-600 dark:text-red-500 mb-4">404</h1>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Halaman Tidak Ditemukan</h2>
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
            Halaman yang kamu cari tidak ada atau sudah dipindahkan.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}"
                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold transition shadow">
                Ke Halaman Order
            </a>
            @auth
            <a href="{{ route('kasir.dashboard') }}"
                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                Ke Dashboard Kasir
            </a>
            @else
            <a href="{{ route('kasir.login') }}"
                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                Login Kasir
            </a>
            @endauth
        </div>
    </div>
</div>
@endsection
