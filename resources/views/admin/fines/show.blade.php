@extends('layouts.app')

@section('title', 'Detail Denda')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Detail Denda</h1>
        <p class="text-gray-600">Informasi denda untuk peminjaman #{{ $fine->borrowing_id }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="content-panel lg:col-span-2 rounded-3xl p-6">
            <h2 class="text-xl font-bold mb-4">Ringkasan Denda</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <p class="text-gray-600">Peminjam</p>
                <p class="font-medium">{{ $fine->borrowing->user->name }}</p>
                <p class="text-gray-600">Buku</p>
                <p class="font-medium">{{ $fine->borrowing->tool->name }}</p>
                <p class="text-gray-600">Jumlah Pinjam</p>
                <p class="font-medium">{{ $fine->borrowing->quantity }}</p>
                <p class="text-gray-600">Jatuh Tempo</p>
                <p class="font-medium">{{ $fine->borrowing->due_date->format('d-m-Y') }}</p>
                <p class="text-gray-600">Hari Terlambat</p>
                <p class="font-medium">{{ $fine->days_late }} hari</p>
                <p class="text-gray-600">Denda Keterlambatan</p>
                <p class="font-medium">Rp {{ number_format($fine->amount_late, 0, ',', '.') }}</p>
                <p class="text-gray-600">Denda Kerusakan</p>
                <p class="font-medium">Rp {{ number_format($fine->amount_damage, 0, ',', '.') }}</p>
                <p class="text-gray-600">Total Denda</p>
                <p class="font-bold text-red-700">Rp {{ number_format($fine->amount_total, 0, ',', '.') }}</p>
                <p class="text-gray-600">Status</p>
                <p>
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($fine->status === 'unpaid') bg-red-100 text-red-800
                        @elseif($fine->status === 'paid') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800
                        @endif
                    ">{{ $fine->status_label }}</span>
                </p>
                <p class="text-gray-600">Dibayar pada</p>
                <p class="font-medium">{{ $fine->paid_at ? $fine->paid_at->format('d-m-Y H:i') : '-' }}</p>
            </div>

            @if($fine->notes_admin)
                <div class="mt-4 rounded-2xl bg-gray-50 p-4">
                    <p class="text-gray-600 text-sm">Catatan Admin</p>
                    <p class="text-gray-900">{{ $fine->notes_admin }}</p>
                </div>
            @endif
        </div>

        <div class="content-panel h-fit rounded-3xl p-6">
            <h3 class="text-lg font-bold mb-4">Aksi</h3>

            @if($fine->status !== 'paid')
                <form method="POST" action="{{ route('admin.fines.markPaid', $fine) }}" class="mb-4 need-confirm"
                    data-confirm-message="Tandai denda ini sebagai lunas?">
                    @csrf
                    @method('PATCH')
                    <textarea name="notes_admin" rows="2" placeholder="Catatan (opsional)"
                        class="mb-2 w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
                    <button type="submit"
                        class="w-full rounded-lg bg-green-600 px-4 py-2 font-medium text-white hover:bg-green-700">Tandai Lunas</button>
                </form>
            @endif

            @if($fine->status !== 'waived')
                <form method="POST" action="{{ route('admin.fines.waive', $fine) }}" class="need-confirm"
                    data-confirm-message="Bebaskan denda ini?">
                    @csrf
                    @method('PATCH')
                    <textarea name="notes_admin" rows="3" required placeholder="Alasan pembebasan denda (wajib)"
                        class="mb-2 w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
                    <button type="submit"
                        class="w-full rounded-lg bg-gray-700 px-4 py-2 font-medium text-white hover:bg-gray-800">Dibebaskan</button>
                </form>
            @endif

            <a href="{{ route('admin.fines.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">< Kembali ke daftar denda</a>
        </div>
    </div>
@endsection
