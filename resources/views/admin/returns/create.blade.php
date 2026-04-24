@extends('layouts.app')

@section('title', 'Catat Pengembalian')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Catat Pengembalian</h1>
    <p class="text-gray-600">Catat pengembalian buku dari peminjam</p>
</div>

<div class="bg-white rounded-lg shadow p-8 max-w-2xl">
    <form method="POST" action="{{ route('admin.returns.store') }}">
        @csrf

        <div class="mb-6">
            <label for="borrowing_id" class="block text-gray-700 font-medium mb-2">Peminjaman <span class="text-red-500">*</span></label>
            <select id="borrowing_id" name="borrowing_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('borrowing_id') border-red-500 @enderror">
                <option value="">Pilih Peminjaman</option>
                @foreach($borrowings as $borrowing)
                    <option value="{{ $borrowing->id }}" @if(old('borrowing_id') == $borrowing->id) selected @endif>
                        {{ $borrowing->user->name }} - {{ $borrowing->tool->name }} ({{ $borrowing->quantity }} pcs)
                    </option>
                @endforeach
            </select>
            @error('borrowing_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="quantity_returned" class="block text-gray-700 font-medium mb-2">Jumlah Dikembalikan <span class="text-red-500">*</span></label>
            <input type="number" id="quantity_returned" name="quantity_returned" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('quantity_returned') border-red-500 @enderror" value="{{ old('quantity_returned') }}">
            @error('quantity_returned') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="condition" class="block text-gray-700 font-medium mb-2">Kondisi Buku <span class="text-red-500">*</span></label>
            <select id="condition" name="condition" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('condition') border-red-500 @enderror">
                <option value="">Pilih Kondisi</option>
                <option value="baik" @if(old('condition') === 'baik') selected @endif>Baik</option>
                <option value="rusak_ringan" @if(old('condition') === 'rusak_ringan') selected @endif>Rusak Ringan</option>
                <option value="rusak_berat" @if(old('condition') === 'rusak_berat') selected @endif>Rusak Berat</option>
            </select>
            @error('condition') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label for="notes" class="block text-gray-700 font-medium mb-2">Catatan</label>
            <textarea id="notes" name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="3">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Simpan</button>
            <a href="{{ route('admin.status.index', ['tab' => 'pengembalian']) }}"
    class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium">
    Batal
</a>
        </div>
    </form>
</div>
@endsection
