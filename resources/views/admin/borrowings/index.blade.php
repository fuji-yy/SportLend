@extends('layouts.app')

@section('title', 'Manage Peminjaman')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Manage Peminjaman</h1>
            <p class="text-gray-600">Kelola semua peminjaman alat SportLend</p>
        </div>
        <a href="{{ route('admin.borrowings.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">+ Tambah Peminjaman</a>
    </div>

    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.borrowings.index') }}" method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari (Nama Peminjam, Alat, Kode)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau kode alat..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Cari</button>
            <a href="{{ route('admin.borrowings.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($borrowings as $borrowing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $borrowing->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $borrowing->tool->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $borrowing->quantity }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $borrowing->borrow_date->format('d-m-Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $borrowing->due_date->format('d-m-Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                                @if($borrowing->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($borrowing->status === 'approved') bg-green-100 text-green-800
                                                @elseif($borrowing->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800
                                                @endif
                                            ">{{ $borrowing->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.borrowings.show', $borrowing) }}"
                                    class="text-blue-600 hover:text-blue-800 mr-3">Lihat</a>
                                <a href="{{ route('admin.borrowings.edit', $borrowing) }}"
                                    class="text-indigo-600 hover:text-indigo-800 mr-3">Edit</a>
                                @if(in_array($borrowing->status, ['pending', 'rejected']))
                                    <form action="{{ route('admin.borrowings.destroy', $borrowing) }}" method="POST"
                                        class="inline need-confirm"
                                        data-confirm-message="Apakah anda yakin ingin menghapus peminjaman ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $borrowings->links() }}
        </div>
    </div>
@endsection