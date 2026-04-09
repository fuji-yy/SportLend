@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Activity Logs</h1>
        <p class="text-gray-600">Pantau semua aktivitas pengguna dalam sistem</p>
    </div>

    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pengguna</label>
                <select name="user_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Pengguna --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Filter</button>
                <a href="{{ route('admin.logs.index') }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $log->user->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                        @if($log->action === 'create') bg-green-100 text-green-800
                                        @elseif($log->action === 'update') bg-blue-100 text-blue-800
                                        @elseif($log->action === 'delete') bg-red-100 text-red-800
                                        @elseif($log->action === 'approve') bg-green-100 text-green-800
                                        @elseif($log->action === 'reject') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">{{ $log->action }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $log->description }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.logs.show', $log) }}"
                                    class="text-blue-600 hover:text-blue-800">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada log aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    </div>
@endsection