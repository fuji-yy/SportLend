@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Detail Pengembalian</h1>
        <p class="text-gray-600">Informasi pengembalian #{{ $return_model->id }}</p>
        <a href="{{ route('admin.returns.index') }}"
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 text-sm">Peminjam</p>
                <p class="text-lg font-medium">{{ $return_model->borrowing->user->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Alat</p>
                <p class="text-lg font-medium">{{ $return_model->borrowing->tool->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Jumlah Dikembalikan</p>
                <p class="text-lg font-medium">{{ $return_model->quantity_returned }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Tanggal Pengembalian</p>
                <p class="text-lg font-medium">{{ $return_model->return_date->format('d-m-Y') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Kondisi Alat</p>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($return_model->condition === 'baik') bg-green-100 text-green-800
                            @elseif($return_model->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif
                        ">{{ $return_model->condition }}</span>
            </div>
        </div>

        @if($return_model->notes)
            <div class="mt-4 p-4 bg-gray-50 rounded">
                <p class="text-gray-600 text-sm">Catatan</p>
                <p class="text-gray-900">{{ $return_model->notes }}</p>
            </div>
        @endif

        @php
            $fine = $return_model->borrowing->fine;
        @endphp
        @if($fine)
            <div class="mt-4 p-4 bg-red-50 border border-red-100 rounded">
                <p class="text-gray-700 text-sm font-semibold mb-2">Ringkasan Denda</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <p class="text-gray-600">Terlambat</p>
                    <p class="font-medium">{{ $fine->days_late }} hari</p>
                    <p class="text-gray-600">Denda Keterlambatan</p>
                    <p class="font-medium">Rp {{ number_format($fine->amount_late, 0, ',', '.') }}</p>
                    <p class="text-gray-600">Denda Kerusakan</p>
                    <p class="font-medium">Rp {{ number_format($fine->amount_damage, 0, ',', '.') }}</p>
                    <p class="text-gray-600">Total Denda</p>
                    <p class="font-semibold text-red-700">Rp {{ number_format($fine->amount_total, 0, ',', '.') }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-6">

        <a href="{{ route('admin.returns.edit', $return_model) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Edit</a>
    </div>
@endsection