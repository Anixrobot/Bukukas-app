<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>BukuKas App</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        candyBlue: '#00B4D8',
                        candyBlueDark: '#0096C7',
                        limeGreen: '#84CC16',
                        limeGreenHover: '#65A30D',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #e5e7eb; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .active-scale:active { transform: scale(0.96); transition: transform 0.1s; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    </style>
</head>

<!-- Menggunakan h-[100dvh] agar menyesuaikan tinggi layar hp sebenarnya -->
<body class="flex justify-center h-screen h-[100dvh] overflow-hidden bg-gray-200">

    <!-- MOBILE APP CONTAINER -->
    <div class="w-full max-w-md bg-gray-50 h-full flex flex-col shadow-2xl relative overflow-hidden">
        
        <!-- HEADER (Fix di atas, pakai shrink-0 agar tidak menyusut) -->
        <header class="shrink-0 px-5 pt-8 pb-4 bg-white flex justify-between items-center z-20 shadow-sm border-b border-gray-100">
            <div>
                <p class="text-xs text-gray-500 font-medium mb-1">Halo, Selamat Datang 👋</p>
                <h1 class="text-xl font-bold text-gray-800">BukuKas App</h1>
            </div>
            
            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="return confirm('Yakin mau keluar?')" class="active-scale w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500 shadow-sm border border-red-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </header>

        <!-- MAIN CONTENT (Scrollable Area, pakai flex-1 agar mengisi ruang tengah otomatis) -->
        <main class="flex-1 overflow-y-auto no-scrollbar relative z-10">
            @yield('konten')
        </main>

        <!-- BOTTOM NAVIGATION (Fix di bawah, pakai shrink-0) -->
        <nav class="shrink-0 bg-white border-t border-gray-200 px-6 py-3 flex justify-around items-center z-20 pb-safe">
            <!-- Kas Kelas -->
            <a href="/kas-kelas" class="flex flex-col items-center gap-1 active-scale {{ Request::is('kas-kelas') || Request::is('kelas') ? 'text-candyBlue' : 'text-gray-400 hover:text-candyBlue' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[10px] font-semibold">Kas Kelas</span>
            </a>
            
            <!-- Kas Pribadi -->
            <a href="/pribadi" class="flex flex-col items-center gap-1 active-scale {{ Request::is('pribadi') ? 'text-candyBlue' : 'text-gray-400 hover:text-candyBlue' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span class="text-[10px] font-semibold">Kas Pribadi</span>
            </a>

            <!-- Siswa -->
            <a href="{{ route('siswa.index') }}" class="flex flex-col items-center gap-1 active-scale {{ request()->routeIs('siswa.*') || Request::is('siswa') ? 'text-candyBlue' : 'text-gray-400 hover:text-candyBlue' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="text-[10px] font-semibold">Siswa</span>
            </a>
        </nav>
    </div>

    <!-- LOADING OVERLAY -->
    <div id="loadingOverlay" class="fixed inset-0 z-[200] hidden items-center justify-center bg-white/80 backdrop-blur-sm">
        <div class="flex flex-col items-center gap-4">
            <!-- Spinner -->
            <div class="relative w-14 h-14">
                <div class="absolute inset-0 rounded-full border-4 border-gray-200"></div>
                <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-[#00B4D8] animate-spin"></div>
            </div>
            <p id="loadingText" class="text-sm font-semibold text-gray-600">Memproses data...</p>
        </div>
    </div>

    <script>
        // Loading overlay handler untuk semua form
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('loadingOverlay');
            const loadingText = document.getElementById('loadingText');

            document.addEventListener('submit', function(e) {
                const form = e.target;

                // Skip form logout (biar nggak nge-block confirm dialog)
                if (form.action && form.action.includes('logout')) return;

                // Cek apakah form punya confirm dialog (hapus data) — 
                // kalau user klik "Cancel" di confirm, jangan tampilkan loading
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (onsubmitAttr && onsubmitAttr.includes('confirm')) {
                    // Confirm sudah di-handle oleh browser sebelum event submit fires,
                    // jadi kalau sampai sini artinya user sudah klik OK
                }

                // Tentukan teks loading berdasarkan konteks form
                const formAction = form.action || '';
                if (formAction.includes('simpan') || formAction.includes('/siswa')) {
                    loadingText.textContent = 'Menyimpan data...';
                } else if (formAction.includes('hapus')) {
                    loadingText.textContent = 'Menghapus data...';
                } else if (formAction.includes('update')) {
                    loadingText.textContent = 'Memperbarui data...';
                } else {
                    loadingText.textContent = 'Memproses data...';
                }

                // Tampilkan overlay
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');

                // Disable semua tombol submit di form ini (anti double-click)
                const buttons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                buttons.forEach(function(btn) {
                    btn.disabled = true;
                    btn.style.opacity = '0.6';
                    btn.style.cursor = 'not-allowed';
                });
            });
        });
    </script>
</body>
</html>