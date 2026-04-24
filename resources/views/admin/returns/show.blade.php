@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
    <div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Detail Pengembalian</h1>
    <p class="text-gray-600 mb-4">Informasi pengembalian #{{ $return_model->id }}</p>

    <a href="{{ request('return_to', route('admin.status.index', ['tab' => 'pengembalian'])) }}"
        class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white shadow hover:bg-blue-700">
        Kembali
    </a>
</div>

    <div class="content-panel max-w-2xl rounded-3xl p-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 text-sm">Peminjam</p>
                <p class="text-lg font-medium">{{ $return_model->borrowing->user->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Buku</p>
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
                <p class="text-gray-600 text-sm">Kondisi Buku</p>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($return_model->condition === 'baik') bg-green-100 text-green-800
                            @elseif($return_model->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif
                        ">{{ $return_model->condition_label }}</span>
            </div>
        </div>

        @if($return_model->notes)
            <div class="mt-4 rounded-2xl bg-gray-50 p-4">
                <p class="text-gray-600 text-sm">Catatan</p>
                <p class="text-gray-900">{{ $return_model->notes }}</p>
            </div>
        @endif

        @php
            $fine = $return_model->borrowing->fine;
        @endphp
        @if($fine)
            <div class="mt-4 rounded-2xl border border-red-100 bg-red-50 p-4">
                <p class="mb-2 text-sm font-semibold text-gray-700">Ringkasan Denda</p>
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
        <a href="{{ route('admin.returns.edit', ['return' => $return_model, 'return_to' => request('return_to')]) }}"
            class="rounded-lg bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700">Edit</a>
        <form action="{{ route('admin.returns.destroy', $return_model) }}" method="POST" class="ml-3 inline need-confirm"
            data-confirm-message="Apakah anda yakin ingin menghapus pengembalian ini?">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">Hapus</button>
        </form>
    </div>
@endsection
