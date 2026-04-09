@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Detail Peminjaman</h1>
    <p class="text-gray-600">Tinjau dan setujui/tolak peminjaman</p>
    <div class="mt-6">
        <a href="{{ route('petugas.borrowings.index') }}"
                class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
                ← Kembali
            </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Informasi Peminjaman</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Peminjam</p>
                    <p class="text-lg font-medium">{{ $borrowing->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $borrowing->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Alat</p>
                    <p class="text-lg font-medium">{{ $borrowing->tool->name }}</p>
                    <p class="text-sm text-gray-600">Kode: {{ $borrowing->tool->code }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Jumlah Diminta</p>
                    <p class="text-lg font-medium">{{ $borrowing->quantity }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Tersedia</p>
                    <p class="text-lg font-medium">{{ $borrowing->tool->available }}</p>
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
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Tindakan</h3>
            <form method="POST" action="{{ route('petugas.borrowings.approve', $borrowing) }}" class="mb-4">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium">
                    ✓ Setujui
                </button>
            </form>
            
            <form method="POST" action="{{ route('petugas.borrowings.reject', $borrowing) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium" onclick="return confirm('Apakah anda yakin ingin menolak?')">
                    ✗ Tolak
                </button>
            </form>
        </div>
    </div>
</div>


@endsection
