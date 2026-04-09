@extends('layouts.app')

@section('title', 'Detail Activity Log')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Detail Activity Log</h1>
    <p class="text-gray-600">Informasi detail log #{{ $log->id }}</p>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <div class="space-y-4">
        <div>
            <p class="text-gray-600 text-sm">Waktu</p>
            <p class="text-lg font-medium">{{ $log->created_at->format('d-m-Y H:i:s') }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">User</p>
            <p class="text-lg font-medium">{{ $log->user->name }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Action</p>
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($log->action === 'create') bg-green-100 text-green-800
                @elseif($log->action === 'update') bg-blue-100 text-blue-800
                @elseif($log->action === 'delete') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif
            ">{{ $log->action }}</span>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Model</p>
            <p class="text-lg font-medium">{{ $log->model ?? '-' }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Deskripsi</p>
            <p class="text-lg font-medium">{{ $log->description }}</p>
        </div>

        @if($log->old_data)
            <div class="mt-4 p-4 bg-red-50 rounded">
                <p class="text-red-800 text-sm font-medium mb-2">Data Lama</p>
                <pre class="text-red-700 text-xs">{{ json_encode($log->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @endif

        @if($log->new_data)
            <div class="mt-4 p-4 bg-green-50 rounded">
                <p class="text-green-800 text-sm font-medium mb-2">Data Baru</p>
                <pre class="text-green-700 text-xs">{{ json_encode($log->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @endif
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('admin.logs.index') }}" class="text-blue-600 hover:text-blue-800">← Kembali</a>
</div>
@endsection
