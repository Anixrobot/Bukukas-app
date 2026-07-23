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
<body class="flex justify-center h-screen overflow-hidden">

    <!-- MOBILE APP CONTAINER -->
    <div class="w-full max-w-md bg-gray-50 h-full flex flex-col relative shadow-2xl overflow-hidden">
        
        <!-- HEADER (Fix di atas) -->
        <header class="px-5 pt-8 pb-4 bg-white flex justify-between items-center z-10 shadow-sm">
            <div>
                <p class="text-xs text-gray-500 font-medium mb-1">Halo, Bosku! 👋</p>
                <h1 class="text-xl font-bold text-gray-800">BukuKas App</h1>
            </div>
            
            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="return confirm('Yakin mau keluar?')" class="active-scale w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500 shadow-sm border border-red-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </header>

        <!-- MAIN CONTENT (Scrollable Area) -->
        <main class="flex-1 overflow-y-auto no-scrollbar pb-24">
            @yield('konten')
        </main>

        <!-- BOTTOM NAVIGATION (Fix di bawah) -->
        <nav class="bg-white border-t border-gray-200 px-6 py-3 flex justify-around items-center z-20 pb-safe absolute bottom-0 w-full">
            <a href="/kas-kelas" class="flex flex-col items-center gap-1 active-scale {{ Request::is('kas-kelas') ? 'text-candyBlue' : 'text-gray-400 hover:text-candyBlue' }}">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                <span class="text-[10px] font-semibold">Kas Kelas</span>
            </a>
            <a href="/pribadi" class="flex flex-col items-center gap-1 active-scale {{ Request::is('pribadi') ? 'text-candyBlue' : 'text-gray-400 hover:text-candyBlue' }}">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1v-1a2 2 0 00-2-2L4 13z"></path></svg>
                <span class="text-[10px] font-semibold">Kas Pribadi</span>
            </a>
        </nav>
    </div>
</body>
</html>