@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="mb-8">
     <a href="{{ route('peminjam.tools.index') }}"
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            ← Kembali
        </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2">
        <div class="content-panel rounded-3xl p-8">
            <div class="mb-6 pb-6 border-b border-gray-200">
                @if($tool->cover_image)
                    <img src="{{ asset('storage/' . $tool->cover_image) }}" alt="{{ $tool->name }}" class="h-64 w-auto max-w-full object-contain rounded-xl bg-slate-50 p-3 shadow mb-5">
                @endif
                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full mb-3">{{ $tool->category->name }}</span>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $tool->name }}</h1>
                <p class="text-gray-600">Kode: <span class="font-medium">{{ $tool->code }}</span></p>
            </div>

            @if($tool->description)
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Deskripsi</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $tool->description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $tool->quantity }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Tersedia</p>
                    <p class="text-2xl font-bold text-green-600">{{ $tool->available }}</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-600 text-sm">Kondisi</p>
                    <span class="text-sm font-medium px-3 py-1 rounded
                        @if($tool->condition === 'baik') bg-green-100 text-green-800
                        @elseif($tool->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif
                    ">{{ $tool->condition_label }}</span>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="content-panel rounded-3xl p-6 sticky top-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Informasi Ketersediaan</h2>
            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-gray-50">
                    <p class="text-sm text-gray-600">Kategori</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $tool->category->name }}</p>
                </div>
                <div class="p-4 rounded-xl bg-gray-50">
                    <p class="text-sm text-gray-600">Jumlah Tersedia</p>
                    <p class="text-lg font-semibold @if($tool->available > 0) text-green-600 @else text-red-600 @endif">{{ $tool->available }}</p>
                </div>
                <div class="p-4 rounded-xl bg-gray-50">
                    <p class="text-sm text-gray-600">Status Buku</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $tool->available > 0 ? 'Bisa dipinjam' : 'Sedang tidak tersedia' }}</p>
                </div>
            </div>

            @if($tool->available > 0)
                <a href="{{ route('peminjam.borrowings.create') }}"
                    class="block mt-6 text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition">
                    Lanjut ke Form Peminjaman
                </a>
            @else
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-800 font-medium">Buku tidak tersedia</p>
                    <p class="text-red-700 mt-2">Maaf, buku ini sedang tidak tersedia. Silakan cek lagi nanti.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
