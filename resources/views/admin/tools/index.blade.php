@extends('layouts.app')

@section('title', 'Data Buku')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Data Buku</h1>
            <p class="text-gray-600">Kelola semua buku di sistem Bookify</p>
        </div>
        <a href="{{ route('admin.tools.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">+ Tambah Buku</a>
    </div>

    <div class="content-panel mb-6 rounded-3xl p-4">
        <form action="{{ route('admin.tools.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Buku</label>
                <input type="text" name="name" value="{{ request('name') }}" placeholder="Ketik judul buku..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Buku</label>
                <select name="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" @selected(request('condition') === 'baik')>Baik</option>
                    <option value="rusak_ringan" @selected(request('condition') === 'rusak_ringan')>Rusak Ringan</option>
                    <option value="rusak_berat" @selected(request('condition') === 'rusak_berat')>Rusak Berat</option>
                </select>
            </div>
            <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Cari</button>
            <a href="{{ route('admin.tools.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium">Atur Ulang</a>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        @forelse($tools as $tool)
            <div class="content-panel overflow-hidden rounded-3xl">
                <div class="grid grid-cols-1 md:grid-cols-[190px_1fr]">
                    <div class="bg-slate-100 flex items-center justify-center overflow-hidden p-4 md:min-h-[270px]">
                        @if($tool->cover_image)
                            <img src="{{ asset('storage/' . $tool->cover_image) }}" alt="{{ $tool->name }}" class="h-auto max-h-[235px] w-auto max-w-full object-contain">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-sky-100 via-white to-blue-200 flex items-center justify-center">
                                <span class="brand-title text-2xl text-blue-800">Bookify</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-slate-500">{{ $tool->code }}</p>
                                <h2 class="text-xl font-bold text-slate-900">{{ $tool->name }}</h2>
                                <p class="text-sm text-slate-600">{{ $tool->category->name }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($tool->condition === 'baik') bg-green-100 text-green-800
                                @elseif($tool->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif
                            ">{{ $tool->condition_label }}</span>
                        </div>
                        <p class="text-sm text-slate-600 leading-7 mb-5">{{ \Illuminate\Support\Str::limit($tool->description ?: 'Belum ada deskripsi buku.', 220, '...') }}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5 text-sm">
                            <div class="bg-slate-50 rounded-xl p-3">
                                <p class="text-slate-500">Jumlah Total</p>
                                <p class="text-lg font-bold text-slate-900">{{ $tool->quantity }}</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3">
                                <p class="text-slate-500">Tersedia</p>
                                <p class="text-lg font-bold @if($tool->available > 0) text-emerald-600 @else text-red-600 @endif">{{ $tool->available }}</p>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-wrap gap-3 justify-start">
                            <a href="{{ route('admin.tools.show', $tool) }}" class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg font-medium">Lihat</a>
                            <a href="{{ route('admin.tools.edit', $tool) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Edit</a>
                            <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST" class="inline need-confirm" data-confirm-message="Apakah anda yakin ingin menghapus buku ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow px-6 py-12 text-center text-gray-500">Tidak ada buku.</div>
        @endforelse
    </div>

    <div class="px-2 py-6">
        {{ $tools->links() }}
    </div>
@endsection
