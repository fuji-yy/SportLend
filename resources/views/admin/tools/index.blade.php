@extends('layouts.app')

@section('title', 'Manage Alat')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Manage Alat</h1>
            <p class="text-gray-600">Kelola semua alat di sistem SportLend</p>
        </div>
        <a href="{{ route('admin.tools.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">+ Tambah Alat</a>
    </div>

    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.tools.index') }}" method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari (Nama, Kode, Deskripsi)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau kode alat..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Cari</button>
            <a href="{{ route('admin.tools.index') }}"
                class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tersedia</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($tools as $tool)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $tool->code }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $tool->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $tool->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $tool->quantity }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($tool->available > 0) bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif
                                    ">{{ $tool->available }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($tool->condition === 'baik') bg-green-100 text-green-800
                                        @elseif($tool->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif
                                    ">{{ $tool->condition }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.tools.edit', $tool) }}"
                                    class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                                <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST"
                                    class="inline need-confirm"
                                    data-confirm-message="Apakah anda yakin ingin menghapus alat ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada alat</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tools->links() }}
        </div>
    </div>
@endsection