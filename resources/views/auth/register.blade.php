@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-10">
    <div class="w-full max-w-md overflow-hidden rounded-[2rem] shadow-2xl">

        <div class="glass-card p-8 md:p-10">
            <h1 class="brand-title mb-2 text-center text-5xl font-extrabold text-transparent bg-gradient-to-r from-blue-700 to-sky-500 bg-clip-text">Bookify</h1>
            <p class="mb-6 text-center text-sm text-gray-500">sistem peminjaman buku</p>
            <h2 class="mb-8 text-center text-3xl font-bold text-gray-900">Daftar</h2>

            @if($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <ul class="space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Username</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100" placeholder="Masukkan username">
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100" placeholder="nama@email.com">
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                    <input type="password" id="password" name="password" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100" placeholder="........">
                    <p class="mt-2 text-xs text-slate-500">Minimal 5 karakter, maksimal 20 karakter. Hanya boleh huruf, angka, tanda `*`, `_`, dan `-`.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100" placeholder="........">
                </div>

                <div>
                    <label for="role" class="mb-2 block text-sm font-semibold text-slate-700">Role</label>
                    <select id="role" name="role" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100">
                        <option value="peminjam" @selected(old('role', 'peminjam') === 'peminjam')>Peminjam</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    </select>
                </div>

                <div id="adminSecretWrapper" class="{{ old('role') === 'admin' ? '' : 'hidden' }}">
                    <label for="admin_secret_code" class="mb-2 block text-sm font-semibold text-slate-700">Kode Rahasia Admin</label>
                    <input type="password" id="admin_secret_code" name="admin_secret_code" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100" placeholder="Masukkan kode rahasia admin">
                    <p class="mt-2 text-xs text-slate-500">Admin wajib masukan kode.</p>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-blue-600 px-4 py-3 font-bold text-white transition hover:bg-blue-700">
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-600">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700">Login di sini</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const adminSecretWrapper = document.getElementById('adminSecretWrapper');
        const adminSecretInput = document.getElementById('admin_secret_code');

        function syncAdminSecretField() {
            const isAdmin = roleSelect.value === 'admin';
            adminSecretWrapper.classList.toggle('hidden', !isAdmin);
            adminSecretInput.toggleAttribute('required', isAdmin);

            if (!isAdmin) {
                adminSecretInput.value = '';
            }
        }

        roleSelect.addEventListener('change', syncAdminSecretField);
        syncAdminSecretField();
    });
</script>
@endsection
