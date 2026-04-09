@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Detail Pengembalian</h1>
        <p class="text-gray-600">Informasi pengembalian alat #{{ $borrowing->id }}</p>
        <div class="mt-6">
            <a href="{{ route('petugas.returns.index') }}"
                    class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
                    ← Kembali
                </a>
                </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-600 text-sm">Peminjam</p>
                <p class="text-lg font-medium">{{ $borrowing->user->name }}</p>
            </div>
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
            <div>
                <p class="text-gray-600 text-sm">Status</p>
                <span
                    class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">{{ $borrowing->status }}</span>
            </div>
        </div>

        @if($return)
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-bold mb-4">Informasi Pengembalian</h3>
                <form method="POST" action="{{ route('petugas.returns.update', $return) }}" class="mb-6">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="quantity_returned" class="block text-gray-700 font-medium mb-2">Jumlah
                                Dikembalikan</label>
                            <input type="number" id="quantity_returned" name="quantity_returned" min="1"
                                max="{{ $borrowing->quantity }}" required
                                value="{{ old('quantity_returned', $return->quantity_returned) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label for="condition" class="block text-gray-700 font-medium mb-2">Kondisi</label>
                            <select id="condition" name="condition" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                <option value="baik" @selected(old('condition', $return->condition) === 'baik')>Baik</option>
                                <option value="rusak_ringan" @selected(old('condition', $return->condition) === 'rusak_ringan')>
                                    Rusak Ringan</option>
                                <option value="rusak_berat" @selected(old('condition', $return->condition) === 'rusak_berat')>
                                    Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="notes" class="block text-gray-700 font-medium mb-2">Catatan</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('notes', $return->notes) }}</textarea>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">Update
                            Pengembalian</button>
                    </div>
                </form>

                <form action="{{ route('petugas.returns.destroy', $return) }}" method="POST" class="inline need-confirm"
                    data-confirm-message="Apakah anda yakin ingin menghapus data pengembalian ini?">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium mb-6">Hapus
                        Pengembalian</button>
                </form>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Jumlah Dikembalikan</p>
                        <p class="text-lg font-medium">{{ $return->quantity_returned }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Tanggal Pengembalian</p>
                        <p class="text-lg font-medium">{{ $return->return_date->format('d-m-Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Kondisi</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                        @if($return->condition === 'baik') bg-green-100 text-green-800
                                        @elseif($return->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif
                                    ">{{ $return->condition }}</span>
                    </div>
                </div>
                @if($borrowing->fine)
                    <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded">
                        <p class="text-gray-700 text-sm font-semibold mb-2">Ringkasan Denda</p>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <p class="text-gray-600">Terlambat</p>
                            <p class="font-medium">{{ $borrowing->fine->days_late }} hari</p>
                            <p class="text-gray-600">Denda Keterlambatan</p>
                            <p class="font-medium">Rp {{ number_format($borrowing->fine->amount_late, 0, ',', '.') }}</p>
                            <p class="text-gray-600">Denda Kerusakan</p>
                            <p class="font-medium">Rp {{ number_format($borrowing->fine->amount_damage, 0, ',', '.') }}</p>
                            <p class="text-gray-600">Total Denda</p>
                            <p class="font-semibold text-red-700">Rp
                                {{ number_format($borrowing->fine->amount_total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endif
                @if($return->notes)
                    <div class="mt-4 p-4 bg-gray-50 rounded">
                        <p class="text-gray-600 text-sm">Catatan</p>
                        <p class="text-gray-900">{{ $return->notes }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="border-t border-gray-200 pt-6">
                <p class="text-gray-600 mb-4">Belum ada pengembalian untuk peminjaman ini</p>
                <form method="POST" action="{{ route('petugas.returns.store', $borrowing) }}" class="max-w-xl">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="quantity_returned" class="block text-gray-700 font-medium mb-2">Jumlah
                                Dikembalikan</label>
                            <input type="number" id="quantity_returned" name="quantity_returned" min="1"
                                max="{{ $borrowing->quantity }}" required value="{{ old('quantity_returned') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label for="condition" class="block text-gray-700 font-medium mb-2">Kondisi</label>
                            <select id="condition" name="condition" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                <option value="">Pilih Kondisi</option>
                                <option value="baik" @selected(old('condition') === 'baik')>Baik</option>
                                <option value="rusak_ringan" @selected(old('condition') === 'rusak_ringan')>Rusak Ringan</option>
                                <option value="rusak_berat" @selected(old('condition') === 'rusak_berat')>Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="notes" class="block text-gray-700 font-medium mb-2">Catatan</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit"
                        class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Simpan
                        Pengembalian</button>
                </form>
            </div>
        @endif
    </div>


@endsection