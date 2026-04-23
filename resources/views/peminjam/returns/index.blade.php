@extends('layouts.app')

@section('title', 'Pengembalian Buku')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-4xl font-bold text-gray-900">Pengembalian Buku</h1>
        <p class="text-gray-600">Lihat buku yang sedang anda pinjam dan kembalikan</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Pengembalian</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($borrowings as $borrowing)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $borrowing->tool->name }}</td>
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
                            @if($borrowing->return)
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">✓ Sudah Dikembalikan</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">⏳ Belum Dikembalikan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if(!$borrowing->return)
                                <a href="{{ route('peminjam.returns.show', $borrowing) }}" class="text-blue-600 hover:text-blue-800 font-medium">Kembalikan</a>
                            @else
                                <span class="text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada buku yang sedang dipinjam</td>
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
