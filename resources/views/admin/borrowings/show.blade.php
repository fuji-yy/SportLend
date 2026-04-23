@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Detail Peminjaman</h1>
        <p class="text-gray-600 mb-10">Kelola peminjaman #{{ $borrowing->id }}</p>
        <a href="{{ request('return_to', route('admin.status.index', ['tab' => 'peminjaman'])) }}"
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
                        <p class="text-gray-600 text-sm">Peminjam</p>
                        <p class="text-lg font-medium">{{ $borrowing->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Buku</p>
                        <p class="text-lg font-medium">{{ $borrowing->tool->name }}</p>
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
                            ">{{ $borrowing->status_label }}</span>
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
            @if($borrowing->status === 'pending')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-4">Tindakan</h3>
                    <form method="POST" action="{{ route('admin.borrowings.updateStatus', $borrowing) }}" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium mb-2">Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('admin.borrowings.updateStatus', $borrowing) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">Tolak</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.borrowings.edit', ['borrowing' => $borrowing, 'return_to' => request('return_to')]) }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Edit</a>
        @if(in_array($borrowing->status, ['pending', 'rejected']))
            <form action="{{ route('admin.borrowings.destroy', $borrowing) }}" method="POST" class="inline need-confirm mr-4"
                data-confirm-message="Apakah anda yakin ingin menghapus peminjaman ini?">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
            </form>
        @endif

    </div>
@endsection
