@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Daftar Buku</h1>
    <p class="text-gray-600">Jelajahi dan ajukan peminjaman buku yang tersedia</p>
</div>

<div class="content-panel mb-6 rounded-3xl p-4">
    <form method="GET" action="{{ route('peminjam.tools.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Buku</label>
            <input type="text" name="name" placeholder="Cari judul buku..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" value="{{ request('name') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
            <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Buku</label>
            <select name="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">Semua Kondisi</option>
                <option value="baik" @selected(request('condition') === 'baik')>Baik</option>
                <option value="rusak_ringan" @selected(request('condition') === 'rusak_ringan')>Rusak Ringan</option>
                <option value="rusak_berat" @selected(request('condition') === 'rusak_berat')>Rusak Berat</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Cari</button>
            <a href="{{ route('peminjam.tools.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium">Atur Ulang</a>
        </div>
    </form>
</div>

<div class="space-y-6">
    @forelse($tools as $tool)
        <div class="content-panel overflow-hidden rounded-3xl transition hover:-translate-y-0.5">
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr]">
                <div class="bg-slate-100 flex items-center justify-center overflow-hidden p-4 md:min-h-[260px]">
                    @if($tool->cover_image)
                        <img src="{{ asset('storage/' . $tool->cover_image) }}" alt="{{ $tool->name }}" class="h-auto max-h-[230px] w-auto max-w-full object-contain">
                    @else
                        <div class="h-full w-full bg-gradient-to-br from-sky-100 via-white to-blue-200 flex items-center justify-center">
                            <span class="brand-title text-xl text-blue-800">Bookify</span>
                        </div>
                    @endif
                </div>
                <div class="p-5 flex flex-col">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div>
                            <h3 class="font-bold text-xl text-slate-900">{{ $tool->name }}</h3>
                            <p class="text-sm text-slate-600">{{ $tool->category->name }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            @if($tool->condition === 'baik') bg-green-100 text-green-800
                            @elseif($tool->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif
                        ">{{ $tool->condition_label }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mb-4">Kode: {{ $tool->code }}</p>
                    <p class="text-sm text-gray-600 leading-7 mb-5">{{ \Illuminate\Support\Str::limit($tool->description ?: 'Belum ada deskripsi buku.', 170, '...') }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-xs text-gray-600">Jumlah Buku</p>
                            <p class="text-xl font-bold text-slate-800">{{ $tool->quantity }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-xs text-gray-600">Tersedia</p>
                            <p class="text-xl font-bold text-green-600">{{ $tool->available }}</p>
                        </div>
                    </div>
                    <div class="mt-auto flex flex-wrap gap-3 justify-start">
                        <a href="{{ route('peminjam.tools.show', $tool) }}" class="text-center bg-slate-200 hover:bg-slate-300 text-slate-800 px-5 py-2 rounded-lg font-medium transition">
                            Lihat
                        </a>
                        <a href="{{ route('peminjam.borrowings.create', [
    'tool_id' => $tool->id,
    'return_to' => url()->current()
]) }}"
class="text-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition">
    Pinjam
</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <p class="text-gray-600 mb-4">Tidak ada buku yang tersedia</p>
            <a href="{{ route('peminjam.tools.index') }}" class="text-blue-600 hover:text-blue-800">Lihat Semua Judul</a>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $tools->links() }}
</div>
@endsection
