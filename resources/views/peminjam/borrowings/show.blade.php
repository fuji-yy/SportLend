@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Detail Peminjaman</h1>
        <p class="text-gray-600">Informasi peminjaman #{{ $borrowing->id }}</p>
    </div>
        <div class="mb-6">
            <a href="{{ route('peminjam.borrowings.index') }}"
                    class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
                    ← Kembali
                </a>
        </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold mb-4">Informasi Peminjaman</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Alat</p>
                        <p class="text-lg font-medium">{{ $borrowing->tool->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Kategori</p>
                        <p class="text-lg font-medium">{{ $borrowing->tool->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Jumlah</p>
                        <p class="text-lg font-medium">{{ $borrowing->quantity }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Status</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($borrowing->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($borrowing->status === 'approved') bg-green-100 text-green-800
                            @elseif($borrowing->status === 'rejected') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800
                            @endif
                        ">{{ $borrowing->status }}</span>
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

                @if($borrowing->purpose)
                    <div class="mt-4 p-4 bg-gray-50 rounded">
                        <p class="text-gray-600 text-sm">Tujuan Peminjaman</p>
                        <p class="text-gray-900">{{ $borrowing->purpose }}</p>
                    </div>
                @endif

                @if($borrowing->notes)
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded">
                        <p class="text-red-800 text-sm font-medium">Catatan Penolakan</p>
                        <p class="text-red-700">{{ $borrowing->notes }}</p>
                    </div>
                @endif

                @if($borrowing->fine)
                    <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded">
                        <p class="text-red-800 text-sm font-semibold mb-2">Informasi Denda</p>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <p class="text-gray-600">Hari Terlambat</p>
                            <p class="font-medium">{{ $borrowing->fine->days_late }} hari</p>
                            <p class="text-gray-600">Denda Keterlambatan</p>
                            <p class="font-medium">Rp {{ number_format($borrowing->fine->amount_late, 0, ',', '.') }}</p>
                            <p class="text-gray-600">Denda Kerusakan</p>
                            <p class="font-medium">Rp {{ number_format($borrowing->fine->amount_damage, 0, ',', '.') }}</p>
                            <p class="text-gray-600">Total</p>
                            <p class="font-semibold text-red-700">Rp
                                {{ number_format($borrowing->fine->amount_total, 0, ',', '.') }}</p>
                            <p class="text-gray-600">Status</p>
                            <p class="font-medium">{{ $borrowing->fine->status }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div>
            @if($borrowing->status === 'pending')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-4">Tindakan</h3>
                    <form method="POST" action="{{ route('peminjam.borrowings.cancel', $borrowing) }}" class="need-confirm"
                        data-confirm-message="Batalkan permintaan peminjaman ini?">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">Batalkan</button>
                    </form>
                </div>
            @elseif($borrowing->status === 'approved')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-4">Kembalikan Alat</h3>
                    <p class="text-gray-600 mb-4">Alat sudah disetujui. Kembalikan alat sesuai jatuh tempo.</p>
                    <a href="{{ route('peminjam.returns.show', $borrowing) }}"
                        class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Kembalikan
                        Sekarang</a>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6">
                    <p class="text-gray-600 text-sm">Tidak ada tindakan yang dapat dilakukan</p>
                </div>
            @endif
        </div>
    </div>

   
@endsection