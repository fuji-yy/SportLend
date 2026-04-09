<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SportLend</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            body {
                background: white;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-white">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="border-b border-gray-200 p-6 no-print">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">SportLend</h1>
                    <p class="text-gray-600">Sistem Informasi Peminjaman Alat</p>
                </div>
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    🖨️ Cetak Laporan
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            @yield('content')
        </div>

        <!-- Footer for Print -->
        <div class="border-t border-gray-200 p-6 text-center text-gray-600 text-sm mt-8">
            <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
