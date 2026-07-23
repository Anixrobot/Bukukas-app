@extends('layout')

@section('konten')
<div class="px-5 pt-5 pb-2">
    @if(session('sukses'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('sukses') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5 shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif
</div>

<!-- WIDGET INFO DATA SISWA -->
<div class="px-5 mb-6">
    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/30 mb-4 relative overflow-hidden">
        <!-- Hiasan background -->
        <svg class="absolute top-0 right-0 opacity-10 transform translate-x-4 -translate-y-4 w-32 h-32" width="128" height="128" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        
        <p class="text-sm font-medium opacity-90 mb-1">👤 Manajemen Data Siswa</p>
        <h2 class="text-3xl font-bold tracking-tight">{{ count($dataSiswa) }} Siswa</h2>
        <p class="text-xs opacity-75 mt-1">Total siswa terdaftar dalam sistem</p>
    </div>
</div>

<!-- FORM TAMBAH SISWA BARU -->
<div class="px-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-5 py-3 border-b border-gray-100">
            <h3 class="font-bold text-gray-700 flex items-center gap-2 text-sm">
                <span>➕</span> Tambah Data Siswa Baru
            </h3>
        </div>
        <div class="p-5">
            <form action="{{ route('siswa.simpan') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">ID Siswa</label>
                        <input type="text" name="id_siswa" placeholder="Cth: S-001" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">NIS (Opsional)</label>
                        <input type="text" name="nis" placeholder="Cth: 12345678" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Cth: Budi Santoso" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none" required>
                </div>
                <button type="submit" class="active-scale w-full bg-candyBlue hover:bg-candyBlueDark text-white font-bold py-3 rounded-xl shadow-md transition-colors">
                    Simpan Data Siswa
                </button>
            </form>
        </div>
    </div>
</div>

<!-- DAFTAR SISWA TERDAFTAR -->
<div class="px-5 mb-24">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-gray-800 text-lg">📋 Daftar Siswa Terdaftar</h3>
        <span class="bg-indigo-100 text-indigo-700 text-[10px] px-2 py-1 rounded-full font-bold shadow-sm">
            {{ count($dataSiswa) }} Orang
        </span>
    </div>

    <div class="space-y-3">
        @forelse($dataSiswa as $siswa)
            @php $siswa = array_pad($siswa, 3, ''); @endphp
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <!-- Avatar -->
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 shrink-0">
                        <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="font-bold text-gray-800 text-sm truncate">{{ $siswa[1] != '' ? $siswa[1] : '-' }}</h4>
                        <div class="flex gap-2 mt-1">
                            <span class="bg-blue-50 text-candyBlue px-2 py-0.5 rounded-md text-[10px] font-semibold border border-blue-100">ID: {{ $siswa[0] != '' ? $siswa[0] : '-' }}</span>
                            @if($siswa[2] != '')
                                <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-md text-[10px] font-semibold border border-gray-200">NIS: {{ $siswa[2] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Tombol Hapus -->
                <div class="shrink-0 ml-2">
                    <form action="{{ route('siswa.hapus', $siswa[0]) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data siswa {{ $siswa[1] }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="active-scale bg-red-100 text-red-600 px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-red-200 transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-xl border border-gray-100 border-dashed">
                <p class="text-gray-400 text-sm">Belum ada data siswa yang terdaftar.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection