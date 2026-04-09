@extends('layouts.app')

@section('title', 'Pantau Pengembalian')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Pantau Pengembalian</h1>
        <p class="text-gray-600">Kelola pengembalian alat dari peminjam</p>
        <a href="{{ route('petugas.returns.monitor') }}"
            class="inline-block mt-3 text-sm text-indigo-600 hover:text-indigo-800">Lihat riwayat pengembalian</a>
    </div>

    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form action="{{ route('petugas.returns.index') }}" method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari (Nama Peminjam, Alat, Kode)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau kode alat..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Cari</button>
            <a href="{{ route('petugas.returns.index') }}"
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
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                                @if($borrowing->due_date->isPast()) bg-red-100 text-red-800
                                                @elseif($borrowing->due_date->diffInDays() <= 3) bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800
                                                @endif
                                            ">{{ $borrowing->due_date->format('d-m-Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('petugas.returns.show', $borrowing) }}"
                                    class="text-blue-600 hover:text-blue-800">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada peminjaman aktif</td>
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