@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Tambah Pengguna</h1>
    <p class="text-gray-600">Buat pengguna baru untuk sistem Bookify</p>
</div>

<div class="bg-white rounded-lg shadow p-8 max-w-2xl">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        
        <div class="mb-6">
            <label for="name" class="block text-gray-700 font-medium mb-2">Username <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror" value="{{ old('name') }}">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="email" class="block text-gray-700 font-medium mb-2">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror" value="{{ old('email') }}">
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password <span class="text-red-500">*</span></label>
            <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror">
            <p class="text-xs text-gray-600 mt-2">Minimal 5 karakter, maksimal 20 karakter. Hanya boleh huruf, angka, * _ -</p>
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="role" class="block text-gray-700 font-medium mb-2">Role <span class="text-red-500">*</span></label>
            <select id="role" name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">Pilih Role</option>
                <option value="admin" @if(old('role') === 'admin') selected @endif>Admin</option>
                <option value="peminjam" @if(old('role') === 'peminjam') selected @endif>Peminjam</option>
            </select>
            @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Simpan</button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection
