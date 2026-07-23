<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Data Siswa - BukuKas App</title>
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
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .active-scale:active { transform: scale(0.96); transition: transform 0.1s; }
        /* Padding bawah agar konten tidak tertutup navbar */
        .content-area { padding-bottom: 90px; } 
    </style>
</head>
<body class="flex justify-center h-screen w-screen overflow-hidden bg-gray-200">
    
    <div class="w-full max-w-md bg-white h-full relative overflow-y-auto shadow-xl content-area">
        
        <!-- Header -->
        <div class="p-6 bg-white sticky top-0 z-10 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-xs text-gray-500 mb-1">Manajemen Pengguna</p>
                <h1 class="text-xl font-bold text-gray-800">Data Siswa</h1>
            </div>
            <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-50 text-red-500 p-2 rounded-full hover:bg-red-100 transition active-scale">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>

        <div class="p-6 pt-2">
            <!-- Alert Notifikasi -->
            @if(session('sukses'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('sukses') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Tambah Siswa -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-8">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span>👤</span> Tambah Data Baru
                </h3>
                <form action="{{ route('siswa.simpan') }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">ID Siswa</label>
                        <input type="text" name="id_siswa" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-candyBlue focus:ring-1 focus:ring-candyBlue outline-none text-sm bg-gray-50" placeholder="Contoh: S-001">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-candyBlue focus:ring-1 focus:ring-candyBlue outline-none text-sm bg-gray-50" placeholder="Contoh: Budi Santoso">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">NIS (Opsional)</label>
                        <input type="text" name="nis" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-candyBlue focus:ring-1 focus:ring-candyBlue outline-none text-sm bg-gray-50" placeholder="Contoh: 12345678">
                    </div>
                    <button type="submit" class="active-scale w-full bg-candyBlue hover:bg-candyBlueDark text-white font-bold py-3 rounded-xl transition mt-2 text-sm">
                        Simpan Data Siswa
                    </button>
                </form>
            </div>

            <!-- Daftar Siswa -->
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>📋</span> Daftar Siswa Terdaftar
            </h3>
            
            <div class="flex flex-col gap-3">
                @forelse($dataSiswa as $siswa)
                    <div class="bg-white border border-gray-100 p-4 rounded-xl shadow-sm flex justify-between items-center">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ isset($siswa[1]) ? $siswa[1] : '-' }}</p>
                            <div class="flex gap-2 mt-1">
                                <span class="bg-blue-50 text-candyBlue px-2 py-0.5 rounded-md text-[10px] font-semibold border border-blue-100">ID: {{ isset($siswa[0]) ? $siswa[0] : '-' }}</span>
                                @if(isset($siswa[2]) && $siswa[2] != '')
                                    <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-md text-[10px] font-semibold border border-gray-200">NIS: {{ $siswa[2] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        Belum ada data siswa yang terdaftar.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Bottom Navigation Bar (Updated dengan 3 Menu) -->
        <div class="fixed bottom-0 w-full max-w-md bg-white border-t border-gray-100 flex justify-around py-3 pb-safe z-50 shadow-[0_-5px_15px_-10px_rgba(0,0,0,0.1)]">
            <a href="{{ url('/kelas') }}" class="flex flex-col items-center gap-1 text-gray-400 hover:text-candyBlue">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[10px] font-semibold">Kas Kelas</span>
            </a>
            <a href="{{ url('/pribadi') }}" class="flex flex-col items-center gap-1 text-gray-400 hover:text-candyBlue">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span class="text-[10px] font-semibold">Kas Pribadi</span>
            </a>
            <a href="{{ route('siswa.index') }}" class="flex flex-col items-center gap-1 text-candyBlue">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="text-[10px] font-semibold">Siswa</span>
            </a>
        </div>
    </div>
</body>
</html>