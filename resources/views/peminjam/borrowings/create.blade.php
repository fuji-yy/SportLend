@extends('layouts.app')

@section('title', 'Buat Peminjaman')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Buat Peminjaman Baru</h1>
    <p class="text-gray-600">Ajukan permintaan peminjaman alat</p>
</div>

<div class="bg-white rounded-lg shadow p-8 max-w-2xl">
    <form method="POST" action="{{ route('peminjam.borrowings.store') }}">
        @csrf
        
        <div class="mb-6">
            <label for="tool_id" class="block text-gray-700 font-medium mb-2">Pilih Alat <span class="text-red-500">*</span></label>
            <select id="tool_id" name="tool_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tool_id') border-red-500 @enderror">
                <option value="">Pilih Alat</option>
                @foreach($tools as $tool)
                    <option value="{{ $tool->id }}" @if(old('tool_id') == $tool->id) selected @endif>
                        {{ $tool->name }} ({{ $tool->available }} tersedia)
                    </option>
                @endforeach
            </select>
            @error('tool_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="quantity" class="block text-gray-700 font-medium mb-2">Jumlah <span class="text-red-500">*</span></label>
            <input type="number" id="quantity" name="quantity" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('quantity') border-red-500 @enderror" value="{{ old('quantity', 1) }}">
            @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="borrow_date" class="block text-gray-700 font-medium mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
            <input type="date" id="borrow_date" name="borrow_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('borrow_date') border-red-500 @enderror" value="{{ old('borrow_date', date('Y-m-d')) }}">
            @error('borrow_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="due_date" class="block text-gray-700 font-medium mb-2">Jatuh Tempo <span class="text-red-500">*</span></label>
            <input type="date" id="due_date" name="due_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('due_date') border-red-500 @enderror" value="{{ old('due_date') }}">
            @error('due_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="purpose" class="block text-gray-700 font-medium mb-2">Tujuan Peminjaman</label>
            <textarea id="purpose" name="purpose" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="4" placeholder="Jelaskan tujuan peminjaman...">{{ old('purpose') }}</textarea>
            @error('purpose') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Ajukan</button>
            <a href="{{ route('peminjam.borrowings.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">Batal</a>
        </div>
    </form>
</div>
@endsection
