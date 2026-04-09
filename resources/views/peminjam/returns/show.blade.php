@extends('layouts.app')

@section('title', 'Kembalikan Alat')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Kembalikan Alat</h1>
    <p class="text-gray-600">Catat pengembalian alat yang anda pinjam</p>
    <div class="mt-6">
        <a href="{{ route('peminjam.borrowings.index') }}"
                class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
                ← Kembali
            </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Daftar Pengembalian</h2>
            <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded">
                <div>
                    <p class="text-gray-600 text-sm">Alat</p>
                    <p class="text-lg font-medium">{{ $borrowing->tool->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Jumlah Dipinjam</p>
                    <p class="text-lg font-medium">{{ $borrowing->quantity }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Tanggal Pinjam</p>
                    <p class="text-lg font-medium">{{ $borrowing->borrow_date->format('d-m-Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Jatuh Tempo</p>
                    <p class="text-lg font-medium">{{ $borrowing->due_date->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow p-6 sticky top-8">
            <h3 class="text-lg font-bold mb-4">Form Pengembalian</h3>
            
            <form method="POST" action="{{ route('peminjam.returns.store', $borrowing) }}">
                @csrf
                
                <div class="mb-4">
                    <label for="quantity_returned" class="block text-gray-700 font-medium mb-2">Jumlah Dikembalikan <span class="text-red-500">*</span></label>
                    <input type="number" id="quantity_returned" name="quantity_returned" required min="1" max="{{ $borrowing->quantity }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('quantity_returned') border-red-500 @enderror" value="{{ old('quantity_returned', $borrowing->quantity) }}">
                    <p class="text-sm text-gray-600 mt-1">Maks. {{ $borrowing->quantity }}</p>
                    @error('quantity_returned') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="condition" class="block text-gray-700 font-medium mb-2">Kondisi Alat <span class="text-red-500">*</span></label>
                    <select id="condition" name="condition" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('condition') border-red-500 @enderror">
                        <option value="">Pilih Kondisi</option>
                        <option value="baik" @if(old('condition') === 'baik') selected @endif>Baik</option>
                        <option value="rusak_ringan" @if(old('condition') === 'rusak_ringan') selected @endif>Rusak Ringan</option>
                        <option value="rusak_berat" @if(old('condition') === 'rusak_berat') selected @endif>Rusak Berat</option>
                    </select>
                    @error('condition') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-gray-700 font-medium mb-2">Catatan (Opsional)</label>
                    <textarea id="notes" name="notes" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="3" placeholder="Catatan atau kerusakan...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium">Kembalikan Alat</button>
            </form>
        </div>
    </div>
</div>


@endsection
