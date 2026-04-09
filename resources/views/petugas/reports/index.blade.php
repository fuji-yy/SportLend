@extends('layouts.app')

@section('title', 'Cetak Laporan')

@section('content')
<style>
    @media print {
        .no-print { display: none !important; }
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
</style>

<div class="mb-8 flex justify-between items-center no-print">
    <div>
        <h1 class="text-4xl font-bold text-gray-900">Cetak Laporan</h1>
        <p class="text-gray-600">Laporan peminjaman dan pengembalian alat</p>
    </div>
    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
        🖨️ Cetak
    </button>
</div>

<!-- Tabs Navigation -->
<div class="mb-6 no-print flex gap-4">
    <button 
        onclick="switchTab('borrowing')" 
        class="px-6 py-3 font-bold rounded-lg transition bg-blue-600 text-white hover:bg-blue-700"
        id="btn-borrowing"
    >
        📋 Laporan Peminjaman
    </button>
    <button 
        onclick="switchTab('return')" 
        class="px-6 py-3 font-bold rounded-lg transition bg-gray-300 text-gray-700 hover:bg-gray-400"
        id="btn-return"
    >
        📤 Laporan Pengembalian
    </button>
</div>

<!-- Borrowing Report Tab -->
<div id="borrowing-tab" class="tab-content active">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
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
                                ">{{ $borrowing->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data peminjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Return Report Tab -->
<div id="return-tab" class="tab-content">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Dikembalikan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($returns as $return)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $return->borrowing->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $return->borrowing->tool->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $return->quantity_returned }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($return->condition === 'baik') bg-green-100 text-green-800
                                    @elseif($return->condition === 'rusak_ringan') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif
                                ">{{ $return->condition }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $return->return_date->format('d-m-Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $return->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengembalian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-center text-gray-600 text-sm mt-6 no-print">
    <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tabs
        document.getElementById('borrowing-tab').classList.remove('active');
        document.getElementById('return-tab').classList.remove('active');
        
        // Reset all buttons to inactive style
        document.getElementById('btn-borrowing').className = 'px-6 py-3 font-bold rounded-lg transition bg-gray-300 text-gray-700 hover:bg-gray-400';
        document.getElementById('btn-return').className = 'px-6 py-3 font-bold rounded-lg transition bg-gray-300 text-gray-700 hover:bg-gray-400';
        
        // Show selected tab
        document.getElementById(tabName + '-tab').classList.add('active');
        
        // Highlight active button
        if (tabName === 'borrowing') {
            document.getElementById('btn-borrowing').className = 'px-6 py-3 font-bold rounded-lg transition bg-blue-600 text-white hover:bg-blue-700';
        } else {
            document.getElementById('btn-return').className = 'px-6 py-3 font-bold rounded-lg transition bg-blue-600 text-white hover:bg-blue-700';
        }
    }
</script>
@endsection
