@extends('layouts.app')

@section('title', 'Cari Alat')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Cari Alat</h1>
    <p class="text-gray-600">Jelajahi dan ajukan peminjaman alat yang tersedia</p>
</div>

<div class="mb-6">
    <form method="GET" action="{{ route('peminjam.tools.search') }}" class="flex gap-2">
        <input type="text" name="q" placeholder="Cari alat..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" value="{{ request('q') }}">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Cari</button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($tools as $tool)
        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition flex flex-col">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                <h3 class="font-bold text-lg">{{ $tool->name }}</h3>
                <p class="text-sm opacity-90">{{ $tool->category->name }}</p>
            </div>
            <div class="p-4 flex-1 flex flex-col">
                <p class="text-sm text-gray-600 mb-2">Kode: {{ $tool->code }}</p>
                <p class="text-sm text-gray-600 mb-4">{{ substr($tool->description, 0, 50) ?? '-' }}</p>
                
                <div class="mb-4 p-3 bg-gray-50 rounded">
                    <p class="text-xs text-gray-600">Tersedia</p>
                    <p class="text-2xl font-bold text-green-600">{{ $tool->available }}</p>
                </div>

                <a href="{{ route('peminjam.tools.show', $tool) }}" class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition mt-auto">
                    Pinjam Sekarang
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-600 mb-4">Tidak ada alat yang tersedia</p>
            <a href="{{ route('peminjam.tools.index') }}" class="text-blue-600 hover:text-blue-800">Lihat Semua Alat</a>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $tools->links() }}
</div>
@endsection
