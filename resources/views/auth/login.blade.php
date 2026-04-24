@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 px-4">
    <div class="glass-card max-w-md w-full rounded-[2rem] p-8">
        <h1 class="brand-title text-5xl font-extrabold text-center bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent mb-2">Bookify</h1>
        <p class="text-center text-sm text-gray-600 mb-6">sistem peminjaman buku</p>
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Username / Email</label>
                <input type="text" id="email" name="email" required class="w-full px-4 py-3 border border-slate-200 rounded-2xl  bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" placeholder="Masukkan username atau email">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-3 border border-slate-200 rounded-2xl  bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100" placeholder="........">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-2xl transition">
                Login
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-bold">Daftar di sini</a></p>
        </div>
    </div>
</div>
@endsection
