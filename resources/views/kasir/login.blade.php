@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow-lg mb-4">
                <span class="text-2xl font-bold text-white">F&B</span>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Kasir Dashboard</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Masuk untuk mengelola pesanan</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p class="text-sm text-red-600 dark:text-red-400">{{ $errors->first() }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('kasir.login.submit') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition"
                        placeholder="Masukkan username">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition"
                        placeholder="Masukkan password">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    <label for="remember" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</label>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold py-3 px-4 rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition shadow-lg">
                    Masuk Dashboard
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <button onclick="toggleDarkMode()" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                <span id="theme-label-dark" class="hidden">Mode Terang</span>
                <span id="theme-label-light">Mode Gelap</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDarkMode() {
    const html = document.documentElement;
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        document.getElementById('theme-label-dark').classList.add('hidden');
        document.getElementById('theme-label-light').classList.remove('hidden');
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        document.getElementById('theme-label-dark').classList.remove('hidden');
        document.getElementById('theme-label-light').classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.documentElement.classList.contains('dark')) {
        document.getElementById('theme-label-dark').classList.remove('hidden');
        document.getElementById('theme-label-light').classList.add('hidden');
    }
});
</script>
@endpush
