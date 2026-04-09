<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SportLend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Sora', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    @if(auth()->check())
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3 pl-8">
                    <div class="flex flex-col">
                        <a href="/"
                            class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent leading-tight">SportLend</a>
                        <span class="text-xs text-gray-500">Sistem Peminjaman Alat Olahraga</span>
                    </div>
                </div>
                <div class="flex items-center pr-8">
                    <form action="{{ route('logout') }}" method="POST" class="inline" id="logoutForm">
                        @csrf
                        <button type="button" onclick="showLogoutModal()"
                            class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg transition">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="flex h-screen bg-gray-100">
            <!-- Sidebar -->
            <aside class="w-64 bg-gradient-to-b from-[#0F2854] to-[#4988C4] shadow-lg">
                <nav class="p-6 space-y-2">
                    <div class="mb-6 px-3">
                        <p class="font-bold text-white" style="font-family: 'Sora', sans-serif; font-size: 18pt;">
                            {{ Auth::user()->name }}</p>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Manage
                            Pengguna</a>
                        <a href="{{ route('admin.categories.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Manage
                            Kategori</a>
                        <a href="{{ route('admin.tools.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Manage Alat</a>
                        <a href="{{ route('admin.borrowings.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Peminjaman</a>
                        <a href="{{ route('admin.returns.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Pengembalian</a>
                        <a href="{{ route('admin.fines.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Manage Denda</a>
                        <a href="{{ route('admin.fines.settings.edit') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Pengaturan
                            Denda</a>
                        <a href="{{ route('admin.logs.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Log
                            Aktifitas</a>
                    @elseif(auth()->user()->isStaff())
                        <a href="{{ route('petugas.dashboard') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Dashboard</a>
                        <a href="{{ route('petugas.borrowings.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Setujui
                            Peminjaman</a>
                        <a href="{{ route('petugas.returns.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Pantau
                            Pengembalian</a>
                        <a href="{{ route('petugas.reports.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Cetak
                            Laporan</a>
                    @else
                        <a href="{{ route('peminjam.dashboard') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Dashboard</a>
                        <a href="{{ route('peminjam.tools.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Cari Alat</a>
                        <a href="{{ route('peminjam.borrowings.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Peminjaman
                            Saya</a>
                        <a href="{{ route('peminjam.returns.index') }}"
                            class="block px-4 py-2 rounded text-white hover:bg-blue-600 transition font-medium">Kembalikan
                            Alat</a>
                    @endif
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 overflow-auto">
                <div class="p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    @else
        @yield('content')
    @endif

    <!-- Logout Confirmation Modal -->
    <div id="logoutModalBackdrop" class="fixed inset-0 hidden z-40" style="background-color: rgba(0,0,0,0.05);\"></div>
    <div id="logoutModal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden z-50">
        <div class="bg-white rounded-lg shadow-2xl p-8 w-96">
            <p class="text-2xl font-bold text-center text-gray-800 mb-8">Apakah Anda yakin ingin logout?</p>
            <div class="flex gap-4 justify-center">
                <button type="button" onclick="submitLogout()"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded-lg transition">
                    Ya
                </button>
                <button type="button" onclick="closeLogoutModal()"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-8 rounded-lg transition">
                    Tidak
                </button>
            </div>
        </div>
    </div>

    <!-- Validation / Message Modal -->
    @php
        $hasErrors = $errors->any();
        $hasErrorMsg = session('error');
        $hasSuccess = session('success');
    @endphp
    <div id="messageModalBackdrop"
        class="fixed inset-0 {{ ($hasErrors || $hasErrorMsg || $hasSuccess) ? '' : 'hidden' }} z-40"
        style="background-color: rgba(0,0,0,0.05);"></div>
    <div id="messageModal"
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 {{ ($hasErrors || $hasErrorMsg || $hasSuccess) ? '' : 'hidden' }} z-50">
        <div class="bg-white rounded-lg shadow-2xl p-6 w-96">
            @if($hasErrors)
                <p class="text-xl font-bold text-center text-gray-800 mb-4">Validasi Gagal</p>
                <div class="mb-4">
                    <ul class="list-disc list-inside text-sm text-gray-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="flex justify-center">
                    <button type="button" onclick="closeMessageModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Tutup</button>
                </div>
            @elseif($hasErrorMsg)
                <p class="text-xl font-bold text-center text-gray-800 mb-4">Error</p>
                <p class="text-center text-gray-700 mb-6">{{ session('error') }}</p>
                <div class="flex justify-center">
                    <button type="button" onclick="closeMessageModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Tutup</button>
                </div>
            @elseif($hasSuccess)
                <p class="text-xl font-bold text-center text-gray-800 mb-4">Sukses</p>
                <p class="text-center text-gray-700 mb-6">{{ session('success') }}</p>
                <div class="flex justify-center">
                    <button type="button" onclick="closeMessageModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Tutup</button>
                </div>
            @endif
        </div>
    </div>

    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
            document.getElementById('logoutModalBackdrop').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
            document.getElementById('logoutModalBackdrop').classList.add('hidden');
        }

        function submitLogout() {
            document.getElementById('logoutForm').submit();
        }
        function closeMessageModal() {
            var m = document.getElementById('messageModal');
            var b = document.getElementById('messageModalBackdrop');
            if (m) m.classList.add('hidden');
            if (b) b.classList.add('hidden');
        }

        // Global confirmation modal for destructive actions
        document.addEventListener('DOMContentLoaded', function () {
            function showConfirm(message, onConfirm) {
                var back = document.getElementById('confirmBackdrop');
                var modal = document.getElementById('confirmModal');
                document.getElementById('confirmMessage').textContent = message;
                back.classList.remove('hidden');
                modal.classList.remove('hidden');
                // attach handlers
                function cleanup() {
                    back.classList.add('hidden');
                    modal.classList.add('hidden');
                    confirmYes.removeEventListener('click', yesHandler);
                    confirmNo.removeEventListener('click', noHandler);
                }
                function yesHandler() { cleanup(); onConfirm(); }
                function noHandler() { cleanup(); }
                var confirmYes = document.getElementById('confirmYes');
                var confirmNo = document.getElementById('confirmNo');
                confirmYes.addEventListener('click', yesHandler);
                confirmNo.addEventListener('click', noHandler);
            }

            document.body.addEventListener('submit', function (e) {
                var form = e.target;
                if (form.classList && form.classList.contains('need-confirm')) {
                    e.preventDefault();
                    var msg = form.getAttribute('data-confirm-message') || 'Apakah Anda yakin?';
                    showConfirm(msg, function () { form.submit(); });
                }
            }, true);

            // also handle clicks on elements with data-confirm (e.g., links/buttons)
            document.body.addEventListener('click', function (e) {
                var el = e.target.closest('[data-confirm]');
                if (!el) return;
                e.preventDefault();
                var msg = el.getAttribute('data-confirm') || 'Apakah Anda yakin?';
                showConfirm(msg, function () {
                    if (el.tagName === 'A' && el.href) window.location = el.href;
                    else if (el.tagName === 'BUTTON' && el.type === 'button' && el.getAttribute('data-action')) {
                        // trigger custom action (not used currently)
                    }
                });
            });
        });
    </script>

    <!-- Confirm Modal -->
    <div id="confirmBackdrop" class="fixed inset-0 hidden z-40" style="background-color: rgba(0,0,0,0.05);"></div>
    <div id="confirmModal" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden z-50">
        <div class="bg-white rounded-lg shadow-2xl p-6 w-96">
            <p id="confirmMessage" class="text-lg font-semibold text-center text-gray-800 mb-6">Apakah Anda yakin?</p>
            <div class="flex gap-4 justify-center">
                <button id="confirmYes"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">Ya</button>
                <button id="confirmNo"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">Tidak</button>
            </div>
        </div>
    </div>
</body>

</html>