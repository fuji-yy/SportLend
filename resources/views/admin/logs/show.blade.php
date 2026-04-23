@extends('layouts.app')

@section('title', 'Detail Log Aktivitas')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900">Detail Log Aktivitas</h1>
    <p class="text-gray-600">Informasi detail log #{{ $log->id }}</p>
</div>

<div class="content-panel max-w-2xl rounded-3xl p-6">
    <div class="space-y-4">
        <div>
            <p class="text-gray-600 text-sm">Waktu</p>
            <p class="text-lg font-medium">{{ $log->created_at->format('d-m-Y H:i:s') }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Pengguna</p>
            <p class="text-lg font-medium">{{ $log->user->name }}</p>
        </div>
        <div>
            <p class="text-gray-600 text-sm">Aksi Sistem</p>
            <span class="px-3 py-1 rounded-full text-sm font-medium
                @if($log->action === 'create') bg-green-100 text-green-800
                @elseif($log->action === 'update') bg-blue-100 text-blue-800
                @elseif($log->action === 'delete') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800
                @endif
            ">{{ $log->action_label }}</span>
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
            <div class="mt-4 rounded bg-red-50 p-4">
                <p class="mb-2 text-sm font-medium text-red-800">Data Lama</p>
                <pre class="text-xs text-red-700">{{ json_encode($log->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @endif

        @if($log->new_data)
            <div class="mt-4 rounded bg-green-50 p-4">
                <p class="mb-2 text-sm font-medium text-green-800">Data Baru</p>
                <pre class="text-xs text-green-700">{{ json_encode($log->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @endif
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('admin.logs.index') }}" class="text-blue-600 hover:text-blue-800">< Kembali</a>
</div>
@endsection
