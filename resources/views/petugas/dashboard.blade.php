@extends('layouts.app')

@section('title', 'Petugas Dashboard')

@section('content')
<div class="mb-4">
    <span class="inline-block bg-gradient-to-r from-[#0F2854] to-[#4988C4] text-white uppercase font-bold px-3 py-1 rounded-lg text-sm tracking-wide" style="font-family: 'Sora', sans-serif">{{ strtoupper(Auth::user()->role) }}</span>
</div>
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Dashboard Petugas</h1>
    <p class="text-gray-600">Selamat datang di halaman petugas SportLend</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 mr-4">
                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Menunggu Persetujuan</p>
                <p class="text-2xl font-bold">{{ $pendingBorrowings }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Peminjaman Aktif</p>
                <p class="text-2xl font-bold">{{ $activeBorrowings }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Pengembalian Terbaru</p>
                <p class="text-2xl font-bold">{{ $recentReturns->count() }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Pengembalian Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentReturns as $return)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $return->borrowing->user->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $return->borrowing->tool->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $return->quantity_returned }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($return->condition === 'baik') bg-green-100 text-green-800
                                @elseif($return->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif
                            ">{{ $return->condition }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $return->return_date->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada pengembalian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
