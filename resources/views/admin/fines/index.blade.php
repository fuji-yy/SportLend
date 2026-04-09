@extends('layouts.app')

@section('title', 'Manage Denda')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Manage Denda</h1>
            <p class="text-gray-600">Kelola denda keterlambatan dan kerusakan peminjaman</p>
        </div>
        <a href="{{ route('admin.fines.settings.edit') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">Atur Harga Denda</a>
    </div>

    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.fines.index') }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pengguna</label>
                <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>
                            {{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua</option>
                    <option value="unpaid" @selected(request('status') === 'unpaid')>Unpaid</option>
                    <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                    <option value="waived" @selected(request('status') === 'waived')>Waived</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">Filter</button>
                <a href="{{ route('admin.fines.index') }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-medium">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterlambatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($fines as $fine)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $fine->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $fine->borrowing->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $fine->borrowing->tool->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $fine->days_late }} hari</td>
                            <td class="px-6 py-4 text-sm font-semibold text-red-700">Rp
                                {{ number_format($fine->amount_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($fine->status === 'unpaid') bg-red-100 text-red-800
                                        @elseif($fine->status === 'paid') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">{{ $fine->status }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.fines.show', $fine) }}"
                                    class="text-blue-600 hover:text-blue-800">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data denda</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $fines->links() }}
        </div>
    </div>
@endsection