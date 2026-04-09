@extends('layouts.app')

@section('title', 'Edit Peminjaman')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900">Edit Peminjaman</h1>
        <p class="text-gray-600">Perbarui data peminjaman #{{ $borrowing->id }}</p>
           <a href="{{ route('admin.borrowings.index') }}"
            class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-8 max-w-3xl">
        <form method="POST" action="{{ route('admin.borrowings.update', $borrowing) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="user_id" class="block text-gray-700 font-medium mb-2">Peminjam <span
                            class="text-red-500">*</span></label>
                    <select id="user_id" name="user_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('user_id') border-red-500 @enderror">
                        <option value="">Pilih Peminjam</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id', $borrowing->user_id) == $user->id)>
                                {{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tool_id" class="block text-gray-700 font-medium mb-2">Alat <span
                            class="text-red-500">*</span></label>
                    <select id="tool_id" name="tool_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('tool_id') border-red-500 @enderror">
                        <option value="">Pilih Alat</option>
                        @foreach($tools as $tool)
                            <option value="{{ $tool->id }}" @selected(old('tool_id', $borrowing->tool_id) == $tool->id)>
                                {{ $tool->name }} (tersedia: {{ $tool->available }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="quantity" class="block text-gray-700 font-medium mb-2">Jumlah <span
                            class="text-red-500">*</span></label>
                    <input type="number" id="quantity" name="quantity" min="1" required
                        value="{{ old('quantity', $borrowing->quantity) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('quantity') border-red-500 @enderror">
                </div>

                <div>
                    <label for="status" class="block text-gray-700 font-medium mb-2">Status <span
                            class="text-red-500">*</span></label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="pending" @selected(old('status', $borrowing->status) === 'pending')>pending</option>
                        <option value="approved" @selected(old('status', $borrowing->status) === 'approved')>approved</option>
                        <option value="rejected" @selected(old('status', $borrowing->status) === 'rejected')>rejected</option>
                        <option value="returned" @selected(old('status', $borrowing->status) === 'returned')>returned</option>
                    </select>
                </div>

                <div>
                    <label for="borrow_date" class="block text-gray-700 font-medium mb-2">Tanggal Pinjam <span
                            class="text-red-500">*</span></label>
                    <input type="date" id="borrow_date" name="borrow_date" required
                        value="{{ old('borrow_date', $borrowing->borrow_date->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('borrow_date') border-red-500 @enderror">
                </div>

                <div>
                    <label for="due_date" class="block text-gray-700 font-medium mb-2">Jatuh Tempo <span
                            class="text-red-500">*</span></label>
                    <input type="date" id="due_date" name="due_date" required
                        value="{{ old('due_date', $borrowing->due_date->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('due_date') border-red-500 @enderror">
                </div>
            </div>

            <div class="mt-6">
                <label for="purpose" class="block text-gray-700 font-medium mb-2">Tujuan</label>
                <textarea id="purpose" name="purpose" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('purpose', $borrowing->purpose) }}</textarea>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-gray-700 font-medium mb-2">Catatan</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">{{ old('notes', $borrowing->notes) }}</textarea>
            </div>

            <div class="mt-8 flex gap-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Update</button>
                <a href="{{ route('admin.borrowings.show', $borrowing) }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium">Batal</a>
            </div>
        </form>
    </div>
@endsection