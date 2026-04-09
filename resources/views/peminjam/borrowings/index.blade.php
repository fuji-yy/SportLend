@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Peminjaman Saya</h1>
            <p class="text-gray-600">Kelola semua peminjaman alat anda</p>
        </div>
        <a href="{{ route('peminjam.borrowings.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">+ Buat Peminjaman Baru</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($borrowings as $borrowing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $borrowing->tool->name }}</td>
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
                                @if($borrowing->fine)
                                    <div class="text-red-700 font-semibold">Rp
                                        {{ number_format($borrowing->fine->amount_total, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">{{ $borrowing->fine->status }}</div>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('peminjam.borrowings.show', $borrowing) }}"
                                    class="text-blue-600 hover:text-blue-800">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada peminjaman</td>
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