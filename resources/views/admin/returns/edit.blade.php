@extends('layouts.app')

@section('title', 'Edit Pengembalian')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Edit Pengembalian</h1>
        <p class="text-gray-600">Perbarui data pengembalian #{{ $return_model->id }}</p>
         <a href="{{ route('admin.returns.index') }}"
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-8 max-w-2xl">
        <form method="POST" action="{{ route('admin.returns.update', $return_model) }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <p class="text-sm text-gray-600">Peminjam</p>
                <p class="font-medium">{{ $return_model->borrowing->user->name }}</p>
                <p class="text-sm text-gray-600 mt-2">Alat: {{ $return_model->borrowing->tool->name }}</p>
            </div>

            <div class="mb-6">
                <label for="quantity_returned" class="block text-gray-700 font-medium mb-2">Jumlah Dikembalikan <span
                        class="text-red-500">*</span></label>
                <input type="number" id="quantity_returned" name="quantity_returned" min="1"
                    max="{{ $return_model->borrowing->quantity }}" required
                    value="{{ old('quantity_returned', $return_model->quantity_returned) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('quantity_returned') border-red-500 @enderror">
            </div>

            <div class="mb-6">
                <label for="condition" class="block text-gray-700 font-medium mb-2">Kondisi Alat <span
                        class="text-red-500">*</span></label>
                <select id="condition" name="condition" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('condition') border-red-500 @enderror">
                    <option value="baik" @selected(old('condition', $return_model->condition) === 'baik')>Baik</option>
                    <option value="rusak_ringan" @selected(old('condition', $return_model->condition) === 'rusak_ringan')>
                        Rusak Ringan</option>
                    <option value="rusak_berat" @selected(old('condition', $return_model->condition) === 'rusak_berat')>Rusak
                        Berat</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-gray-700 font-medium mb-2">Catatan</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('notes', $return_model->notes) }}</textarea>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Update</button>
                <a href="{{ route('admin.returns.show', $return_model) }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">Batal</a>
            </div>
        </form>
    </div>
@endsection