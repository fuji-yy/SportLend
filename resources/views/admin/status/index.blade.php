@extends('layouts.app')

@section('title', 'Transaksi Buku')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Transaksi Buku</h1>
            <p class="text-gray-600">Pantau data peminjaman dan pengembalian buku.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.borrowings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">+ Tambah Peminjaman</a>
            <a href="{{ route('admin.returns.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium">+ Catat Pengembalian</a>
        </div>
    </div>

    <div class="mb-6 flex gap-4">
        <a href="{{ route('admin.status.index', ['tab' => 'peminjaman']) }}"
            class="px-6 py-3 rounded-xl font-semibold transition {{ $tab === 'peminjaman' ? 'bg-blue-600 text-white shadow' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}">
            Status Peminjaman
        </a>
        <a href="{{ route('admin.status.index', ['tab' => 'pengembalian']) }}"
            class="px-6 py-3 rounded-xl font-semibold transition {{ $tab === 'pengembalian' ? 'bg-blue-600 text-white shadow' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}">
            Status Pengembalian
        </a>
    </div>

    <div class="content-panel mb-6 rounded-3xl p-4">
        <form action="{{ route('admin.status.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Data</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama peminjam, judul buku, atau kode buku"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            @if($tab === 'peminjaman')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Peminjaman</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua</option>
                        <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                        <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                        <option value="returned" @selected(request('status') === 'returned')>Selesai</option>
                    </select>
                </div>
                <div></div>
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Buku</label>
                    <select name="condition" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" @selected(request('condition') === 'baik')>Baik</option>
                        <option value="rusak_ringan" @selected(request('condition') === 'rusak_ringan')>Rusak Ringan</option>
                        <option value="rusak_berat" @selected(request('condition') === 'rusak_berat')>Rusak Berat</option>
                    </select>
                </div>
                <div></div>
            @endif
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Cari</button>
                <a href="{{ route('admin.status.index', ['tab' => $tab]) }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium">Atur Ulang</a>
            </div>
        </form>
    </div>

    <div class="content-panel overflow-hidden rounded-3xl">
        <div class="overflow-x-auto">
            @if($tab === 'peminjaman')
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($borrowings as $borrowing)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $borrowing->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $borrowing->tool->name }}</td>
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
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.borrowings.show', ['borrowing' => $borrowing, 'return_to' => request()->fullUrl()]) }}" class="text-blue-600 hover:text-blue-800 mr-3">Lihat</a>
                                    <a href="{{ route('admin.borrowings.edit', ['borrowing' => $borrowing, 'return_to' => request()->fullUrl()]) }}" class="text-indigo-600 hover:text-indigo-800 mr-3">Edit</a>
                                    @if(in_array($borrowing->status, ['pending', 'rejected']))
                                        <form action="{{ route('admin.borrowings.destroy', $borrowing) }}" method="POST" class="inline need-confirm" data-confirm-message="Apakah anda yakin ingin menghapus peminjaman ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Denda</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
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
                                    ">{{ $return->condition_label }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($return->borrowing->fine)
                                        Rp {{ number_format($return->borrowing->fine->amount_total, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.returns.show', ['return' => $return, 'return_to' => request()->fullUrl()]) }}" class="text-emerald-600 hover:text-emerald-800 mr-3">Lihat</a>
                                    <a href="{{ route('admin.returns.edit', ['return' => $return, 'return_to' => request()->fullUrl()]) }}" class="text-indigo-600 hover:text-indigo-800 mr-3">Edit</a>
                                    <form action="{{ route('admin.returns.destroy', $return) }}" method="POST" class="inline need-confirm" data-confirm-message="Apakah anda yakin ingin menghapus pengembalian ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data pengembalian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            @if($tab === 'peminjaman')
                {{ $borrowings->links() }}
            @else
                {{ $returns->links() }}
            @endif
        </div>
    </div>
@endsection
