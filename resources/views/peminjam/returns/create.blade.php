@extends('layouts.app')

@section('title', 'Buat Pengembalian Buku')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-4xl font-bold text-gray-900">Buat Pengembalian Buku</h1>
        <p class="text-gray-600">Pilih buku yang akan dikembalikan dan isi form pengembalian</p>
    </div>
    <a href="{{ route('peminjam.returns.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">← Kembali</a>
</div>

@if($borrowings->isEmpty())
    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-6 py-4 rounded-lg">
        <p class="text-center">Anda tidak memiliki peminjaman aktif yang dapat dikembalikan.</p>
        <p class="text-center mt-2"><a href="{{ route('peminjam.borrowings.index') }}" class="text-blue-600 hover:text-blue-800 font-bold">Lihat peminjaman Anda</a></p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">Daftar Peminjaman Aktif</h2>
                <div class="space-y-4">
                    @foreach($borrowings as $borrowing)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 cursor-pointer" onclick="selectBorrowing({{ $borrowing->id }}, '{{ $borrowing->tool->name }}', {{ $borrowing->quantity }})">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $borrowing->tool->name }}</h3>
                                    <p class="text-sm text-gray-600">Kategori: {{ $borrowing->tool->category->name }}</p>
                                    <div class="mt-2 grid grid-cols-3 gap-2 text-sm">
                                        <div>
                                            <p class="text-gray-600">Qty Dipinjam</p>
                                            <p class="font-medium">{{ $borrowing->quantity }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Tanggal Pinjam</p>
                                            <p class="font-medium">{{ $borrowing->borrow_date->format('d-m-Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600">Jatuh Tempo</p>
                                            <p class="font-medium">{{ $borrowing->due_date->format('d-m-Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <input type="radio" name="borrowing_id" value="{{ $borrowing->id }}" class="mt-2" onclick="selectBorrowing({{ $borrowing->id }}, '{{ $borrowing->tool->name }}', {{ $borrowing->quantity }})">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                <h3 class="text-lg font-bold mb-4">Form Pengembalian</h3>
                
                <form method="POST" id="returnForm">
                    @csrf
                    <input type="hidden" id="borrowing_id_field" name="borrowing_id">
                    
                    <div class="mb-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">Buku Terpilih</p>
                        <p class="font-bold text-gray-900" id="selected_tool">-</p>
                    </div>

                    <div class="mb-4">
                        <label for="quantity_returned" class="block text-gray-700 font-medium mb-2">Jumlah Dikembalikan <span class="text-red-500">*</span></label>
                        <input type="number" id="quantity_returned" name="quantity_returned" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('quantity_returned') border-red-500 @enderror" placeholder="Jumlah">
                        <p class="text-sm text-gray-600 mt-1">Maks. <span id="max_quantity">-</span></p>
                        @error('quantity_returned') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="condition" class="block text-gray-700 font-medium mb-2">Kondisi Buku <span class="text-red-500">*</span></label>
                        <select id="condition" name="condition" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('condition') border-red-500 @enderror">
                            <option value="">Pilih Kondisi</option>
                            <option value="baik" @if(old('condition') === 'baik') selected @endif>Baik</option>
                            <option value="rusak_ringan" @if(old('condition') === 'rusak_ringan') selected @endif>Rusak Ringan</option>
                            <option value="rusak_berat" @if(old('condition') === 'rusak_berat') selected @endif>Rusak Berat</option>
                        </select>
                        @error('condition') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-gray-700 font-medium mb-2">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('notes') border-red-500 @enderror" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" id="submitBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                        Kembalikan Buku
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectBorrowing(borrowingId, toolName, maxQty) {
            document.getElementById('borrowing_id_field').value = borrowingId;
            document.getElementById('selected_tool').textContent = toolName;
            document.getElementById('max_quantity').textContent = maxQty;
            document.getElementById('quantity_returned').max = maxQty;
            document.getElementById('quantity_returned').value = '';
            document.getElementById('submitBtn').disabled = false;
            
            // Update form action URL
            const form = document.getElementById('returnForm');
            form.action = `{{ route('peminjam.returns.store', [':id' => 'ID']) }}`.replace(':id', borrowingId);
        }
    </script>
@endif
@endsection
