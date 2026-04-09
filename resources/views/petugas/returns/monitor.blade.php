@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Riwayat Pengembalian</h1>
        <p class="text-gray-600">Pantau semua pengembalian alat yang telah dicatat</p>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($returns as $return)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $return->borrowing->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $return->borrowing->tool->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $return->quantity_returned }}/{{ $return->borrowing->quantity }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $return->return_date->format('d-m-Y H:i') ?? $return->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                            @if($return->condition === 'baik') bg-green-100 text-green-800
                                            @elseif($return->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif
                                        ">{{ $return->condition }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ substr($return->notes ?? '-', 0, 30) }}{{ strlen($return->notes ?? '') > 30 ? '...' : '' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('petugas.returns.show', $return->borrowing_id) }}"
                                    class="text-blue-600 hover:text-blue-800">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada riwayat pengembalian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $returns->links() }}
        </div>
    </div>
@endsection