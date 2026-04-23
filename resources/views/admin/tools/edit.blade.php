@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Edit Buku</h1>
    <p class="text-gray-600">Perbarui data buku {{ $tool->name }}</p>
</div>

<div class="bg-white rounded-lg shadow p-8 max-w-2xl">
    <form method="POST" action="{{ route('admin.tools.update', $tool) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label for="category_id" class="block text-gray-700 font-medium mb-2">Kategori <span class="text-red-500">*</span></label>
            <select id="category_id" name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if(old('category_id', $tool->category_id) == $category->id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="code" class="block text-gray-700 font-medium mb-2">Kode Buku <span class="text-red-500">*</span></label>
            <input type="text" id="code" name="code" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('code') border-red-500 @enderror" value="{{ old('code', $tool->code) }}">
            @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="name" class="block text-gray-700 font-medium mb-2">Judul Buku <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror" value="{{ old('name', $tool->name) }}">
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-gray-700 font-medium mb-2">Deskripsi</label>
            <textarea id="description" name="description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="3">{{ old('description', $tool->description) }}</textarea>
        </div>

        <div class="mb-6">
            <label for="cover_image" class="block text-gray-700 font-medium mb-2">Foto Buku</label>
            @if($tool->cover_image)
                <img src="{{ asset('storage/' . $tool->cover_image) }}" alt="{{ $tool->name }}" class="w-28 h-40 object-cover rounded-lg shadow mb-3">
            @endif
            <input type="file" id="cover_image" name="cover_image" accept=".png,.jpg,.jpeg,image/png,image/jpeg" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('cover_image') border-red-500 @enderror bg-white">
            <p class="text-xs text-gray-600 mt-2">Unggah file baru jika ingin mengganti sampul. Format: PNG, JPG, JPEG. Maksimal 2 MB.</p>
            @error('cover_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="quantity" class="block text-gray-700 font-medium mb-2">Jumlah Total <span class="text-red-500">*</span></label>
            <input type="number" id="quantity" name="quantity" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('quantity') border-red-500 @enderror" value="{{ old('quantity', $tool->quantity) }}">
            @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="condition" class="block text-gray-700 font-medium mb-2">Kondisi <span class="text-red-500">*</span></label>
            <select id="condition" name="condition" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('condition') border-red-500 @enderror">
                <option value="baik" @if(old('condition', $tool->condition) === 'baik') selected @endif>Baik</option>
                <option value="rusak_ringan" @if(old('condition', $tool->condition) === 'rusak_ringan') selected @endif>Rusak Ringan</option>
                <option value="rusak_berat" @if(old('condition', $tool->condition) === 'rusak_berat') selected @endif>Rusak Berat</option>
            </select>
            @error('condition') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Perbarui</button>
            <a href="{{ route('admin.tools.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection
