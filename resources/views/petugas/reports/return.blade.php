@extends('layouts.app')

@section('title', 'Laporan Pengembalian')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
    }
</style>

<div class="mb-8 flex justify-between items-center no-print">
    <div>
        <h1 class="text-4xl font-bold text-gray-900">Laporan Pengembalian</h1>
        <p class="text-gray-600">Data lengkap pengembalian alat</p>
    </div>
    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
        🖨️ Cetak Laporan
    </button>
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
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($returns as $return)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $return->borrowing->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $return->borrowing->tool->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $return->quantity_returned }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $return->return_date->format('d-m-Y') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($return->condition === 'baik') bg-green-100 text-green-800
                                @elseif($return->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif
                            ">{{ $return->condition }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengembalian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="text-center text-gray-600 text-sm mt-6 no-print">
    <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
</div>
@endsection
