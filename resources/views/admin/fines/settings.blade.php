@extends('layouts.app')

@section('title', 'Pengaturan Denda')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Pengaturan Denda</h1>
        <p class="text-gray-600">Atur tarif denda agar kalkulasi pengembalian sesuai kebijakan terbaru</p>
    </div>

    <div class="max-w-3xl bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.fines.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label for="late_fee_per_day" class="block text-sm font-medium text-gray-700 mb-2">Denda keterlambatan per
                    hari (Rp)</label>
                <input type="number" min="0" id="late_fee_per_day" name="late_fee_per_day"
                    value="{{ old('late_fee_per_day', $setting->late_fee_per_day) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="damage_light_per_item" class="block text-sm font-medium text-gray-700 mb-2">Denda rusak ringan
                    per item (Rp)</label>
                <input type="number" min="0" id="damage_light_per_item" name="damage_light_per_item"
                    value="{{ old('damage_light_per_item', $setting->damage_light_per_item) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="damage_heavy_per_item" class="block text-sm font-medium text-gray-700 mb-2">Denda rusak berat
                    per item (Rp)</label>
                <input type="number" min="0" id="damage_heavy_per_item" name="damage_heavy_per_item"
                    value="{{ old('damage_heavy_per_item', $setting->damage_heavy_per_item) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Simpan
                    Pengaturan</button>
                <a href="{{ route('admin.fines.index') }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium">Kembali</a>
            </div>
        </form>
    </div>
@endsection