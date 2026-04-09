@extends('layouts.app')

@section('title', 'Detail Alat')

@section('content')
<div class="mb-8">
     <a href="{{ route('peminjam.tools.index') }}"
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            ← Kembali
        </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow p-8">
            <div class="mb-6 pb-6 border-b border-gray-200">
                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full mb-3">{{ $tool->category->name }}</span>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $tool->name }}</h1>
                <p class="text-gray-600">Kode: <span class="font-medium">{{ $tool->code }}</span></p>
            </div>

            @if($tool->description)
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Deskripsi</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $tool->description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tool->quantity }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Tersedia</p>
                    <p class="text-2xl font-bold text-green-600">{{ $tool->available }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Kondisi</p>
                    <span class="text-sm font-medium px-3 py-1 rounded
                        @if($tool->condition === 'baik') bg-green-100 text-green-800
                        @elseif($tool->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif
                    ">{{ $tool->condition }}</span>
                </div>
            </div>
        </div>
    </div>

    <div>
        @if($tool->available > 0)
            <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Ajukan Peminjaman</h2>
                
                <form method="POST" action="{{ route('peminjam.borrowings.store') }}">
                    @csrf
                    <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                    
                    <div class="mb-4">
                        <label for="quantity" class="block text-gray-700 font-medium mb-2">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" id="quantity" name="quantity" required min="1" max="{{ $tool->available }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('quantity') border-red-500 @enderror" value="{{ old('quantity', 1) }}">
                        <p class="text-sm text-gray-600 mt-1">Maks. {{ $tool->available }} tersedia</p>
                        @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="borrow_date" class="block text-gray-700 font-medium mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
                        <input type="date" id="borrow_date" name="borrow_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('borrow_date') border-red-500 @enderror" value="{{ old('borrow_date', date('Y-m-d')) }}">
                        @error('borrow_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="due_date" class="block text-gray-700 font-medium mb-2">Jatuh Tempo <span class="text-red-500">*</span></label>
                        <input type="date" id="due_date" name="due_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('due_date') border-red-500 @enderror" value="{{ old('due_date') }}">
                        @error('due_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="purpose" class="block text-gray-700 font-medium mb-2">Tujuan Peminjaman</label>
                        <textarea id="purpose" name="purpose" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="3" placeholder="Jelaskan tujuan peminjaman...">{{ old('purpose') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition">
                        Ajukan Peminjaman
                    </button>
                </form>
            </div>
        @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 sticky top-8">
                <p class="text-red-800 font-medium">Alat tidak tersedia</p>
                <p class="text-red-700 mt-2">Maaf, alat ini sedang tidak tersedia. Silakan cek lagi nanti.</p>
            </div>
        @endif
    </div>
</div>
@endsection
