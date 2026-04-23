@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Detail Buku</h1>
    <p class="text-gray-600">Informasi lengkap buku {{ $tool->name }}</p>
    <div class="mt-6">
        <a href="{{ route('admin.tools.index') }}"
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            ← Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow overflow-hidden border border-slate-100">
    <div class="grid grid-cols-1 md:grid-cols-[260px_1fr]">
        <div class="bg-slate-100 min-h-[360px] flex items-center justify-center overflow-hidden">
            @if($tool->cover_image)
                <img src="{{ asset('storage/' . $tool->cover_image) }}" alt="{{ $tool->name }}" class="w-full h-full object-cover">
            @else
                <div class="h-full w-full bg-gradient-to-br from-sky-100 via-white to-blue-200 flex items-center justify-center">
                    <span class="brand-title text-3xl text-blue-800">Bookify</span>
                </div>
            @endif
        </div>
        <div class="p-8">
            <div class="mb-6">
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">{{ $tool->code }}</p>
                <h2 class="text-3xl font-bold text-slate-900">{{ $tool->name }}</h2>
                <p class="text-slate-600 mt-1">{{ $tool->category->name }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-slate-500 text-sm">Jumlah Total</p>
                    <p class="text-xl font-bold text-slate-900">{{ $tool->quantity }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-slate-500 text-sm">Tersedia</p>
                    <p class="text-xl font-bold @if($tool->available > 0) text-emerald-600 @else text-red-600 @endif">{{ $tool->available }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-slate-500 text-sm">Kondisi</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-medium
                        @if($tool->condition === 'baik') bg-green-100 text-green-800
                        @elseif($tool->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif
                    ">{{ $tool->condition_label }}</span>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-900 mb-3">Deskripsi</h3>
                <p class="text-slate-600 leading-7">{{ $tool->description ?: 'Belum ada deskripsi buku.' }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.tools.edit', $tool) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium">Edit</a>
                <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST" class="inline need-confirm" data-confirm-message="Apakah anda yakin ingin menghapus buku ini?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-medium">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
