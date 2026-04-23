@extends('layouts.app')

@section('title', 'Dashboard Peminjam')

@section('content')
<div class="mb-4">
    <span class="inline-block bg-gradient-to-r from-[#0F2854] to-[#4988C4] text-white uppercase font-bold px-3 py-1 rounded-lg text-sm tracking-wide" style="font-family: 'Sora', sans-serif">{{ strtoupper(Auth::user()->role) }}</span>
</div>
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Dashboard Peminjam</h1>
    <p class="text-gray-600">Selamat datang di halaman peminjam Bookify</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="content-panel rounded-3xl p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h4l3 3 3-3h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14h-4l-3 3-3-3H5V4h14v12z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Peminjaman</p>
                <p class="text-2xl font-bold">{{ $totalBorrowings }}</p>
            </div>
        </div>
    </div>

    <div class="content-panel rounded-3xl p-6">
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

    <div class="content-panel rounded-3xl p-6">
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

    <div class="content-panel rounded-3xl p-6">
        <a href="{{ route('peminjam.tools.index') }}" class="flex items-center h-full">
            <div class="p-3 rounded-full bg-sky-100 mr-4">
                <svg class="w-8 h-8 text-sky-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h4l3 3 3-3h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14h-4l-3 3-3-3H5V4h14v12z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Daftar Buku</p>
                <p class="text-lg font-bold text-blue-600">Lihat</p>
            </div>
        </a>
    </div>
</div>

<div class="content-panel overflow-hidden rounded-3xl">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900">Peminjaman Terakhir</h2>
        <a href="{{ route('peminjam.borrowings.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentBorrowings as $borrowing)
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
                            ">{{ $borrowing->status_label }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada peminjaman</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
