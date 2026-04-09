@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-4">
    <span class="inline-block bg-gradient-to-r from-[#0F2854] to-[#4988C4] text-white uppercase font-bold px-3 py-1 rounded-lg text-sm tracking-wide" style="font-family: 'Sora', sans-serif">{{ strtoupper(Auth::user()->role) }}</span>
</div>
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-2">Dashboard Admin</h1>
    <p class="text-gray-600">Selamat datang di halaman administrasi SportLend</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Pengguna</p>
                <p class="text-2xl font-bold">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h4l3 3 3-3h4c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Total Alat</p>
                <p class="text-2xl font-bold">{{ $totalTools }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 mr-4">
                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24"><path d="M7 13h10v-2H7v2zm0-4h4V7H7v2zm10-2v2h4V7h-4zm-4 4v2h4v-2h-4z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Peminjaman Aktif</p>
                <p class="text-2xl font-bold">{{ $activeBorrowings }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 mr-4">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Menunggu Persetujuan</p>
                <p class="text-2xl font-bold">{{ $pendingBorrowings }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Aktivitas Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentActivities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $activity->user->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                @if($activity->action === 'create') bg-green-100 text-green-800
                                @elseif($activity->action === 'update') bg-blue-100 text-blue-800
                                @elseif($activity->action === 'delete') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif
                            ">{{ $activity->action }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $activity->description }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada aktivitas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
