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

<!-- WIDGET ANALITIK SALDO -->
<div class="px-5 mb-6">
    <!-- Card Saldo Utama -->
    <div class="bg-gradient-to-br from-candyBlue to-candyBlueDark rounded-2xl p-6 text-white shadow-lg shadow-candyBlue/30 mb-4 relative overflow-hidden">
        <!-- Hiasan background -->
        <svg class="absolute top-0 right-0 opacity-10 transform translate-x-4 -translate-y-4 w-32 h-32" width="128" height="128" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1v-1a2 2 0 00-2-2L4 13z"></path></svg>
        
        <p class="text-sm font-medium opacity-90 mb-1">💼 Sisa Saldo Kas Kelas</p>
        <h2 class="text-3xl font-bold tracking-tight">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
    </div>

    <!-- Pemasukan & Pengeluaran (Side by side) -->
    <div class="flex gap-3">
        <div class="flex-1 bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500 shrink-0">
                <svg class="w-6 h-6" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Uang Masuk</p>
                <p class="text-sm font-bold text-gray-800 truncate">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="flex-1 bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-500 shrink-0">
                <svg class="w-6 h-6" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Uang Keluar</p>
                <p class="text-sm font-bold text-gray-800 truncate">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- FORM INPUT DATA -->
<div class="px-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-5 py-3 border-b border-gray-100">
            <h3 class="font-bold text-gray-700 flex items-center gap-2 text-sm">
                <span>🏫</span> Input Transaksi Kelas
            </h3>
        </div>
        <div class="p-5">
            <form action="/simpan-kas-kelas" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Jenis Transaksi</label>
                        <select name="jenis" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none" required>
                            <option value="">Pilih</option>
                            <option value="Pemasukan">Uang Masuk</option>
                            <option value="Pengeluaran">Uang Keluar</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">ID Siswa / Nama</label>
                    <input type="text" name="id_siswa" placeholder="Cth: Budi atau S-001" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" placeholder="25000" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="2" placeholder="Cth: Bayar kas bulan Juli..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none" required></textarea>
                </div>
                <button type="submit" class="active-scale w-full bg-candyBlue hover:bg-candyBlueDark text-white font-bold py-3 rounded-xl shadow-md transition-colors">
                    Simpan Data Kas Kelas
                </button>
            </form>
        </div>
    </div>
</div>

<!-- WIDGET DAFTAR BELUM BAYAR (Dikeluarin dari loop tabel biar bener) -->
<div class="px-5 mb-6">
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
        <div class="bg-red-50 px-5 py-3 border-b border-red-100 flex justify-between items-center">
            <h3 class="font-bold text-red-700 flex items-center gap-2 text-sm">
                <span>⚠️</span> Belum Bayar ({{ date('M Y', strtotime(($bulanAktif ?? date('Y-m')) . '-01')) }})
            </h3>
            <span class="bg-red-500 text-white text-[10px] px-2 py-1 rounded-full font-bold shadow-sm">
                {{ count($belumBayar ?? []) }} Orang
            </span>
        </div>
        <div class="p-4 space-y-2 max-h-48 overflow-y-auto no-scrollbar">
            @forelse($belumBayar ?? [] as $namaNunggak)
                <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                    <span class="text-sm font-semibold text-gray-700">{{ $namaNunggak }}</span>
                    
                    @php
                        $teksTagih = "Assalamu'alaikum, halo " . $namaNunggak . ". %0A%0AIngin mengingatkan untuk pembayaran Kas Kelas bulan " . date('F Y', strtotime(($bulanAktif ?? date('Y-m')) . '-01')) . " belum tercatat nih. Yuk segera dilunaskan! 🙏";
                    @endphp
                    
                    <a href="https://wa.me/?text={{ $teksTagih }}" target="_blank" class="active-scale bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1 transition-colors">
                        <svg class="w-3.5 h-3.5" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.005-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg> 
                        Tagih
                    </a>
                </div>
            @empty
                <div class="text-center py-6">
                    <p class="text-gray-400 text-sm font-semibold">🎉 Keren! Semuanya udah lunas!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- RIWAYAT & FILTER -->
<div class="px-5 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-gray-800 text-lg">📋 Riwayat Kas Kelas</h3>
    </div>

    @php
        $pesanWA = "Assalamu'alaikum Ibu-ibu, ini update Laporan Kas Kelas kita yaa 📢%0A%0A";
        $pesanWA .= "💰 Total Pemasukan: Rp " . number_format($totalPemasukan, 0, ',', '.') . "%0A";
        $pesanWA .= "💸 Total Pengeluaran: Rp " . number_format($totalPengeluaran, 0, ',', '.') . "%0A";
        $pesanWA .= "💳 Sisa Saldo Kas: Rp " . number_format($saldo, 0, ',', '.') . "%0A%0A";
        $pesanWA .= "Terima kasih! 🙏";
    @endphp

    <form action="/kas-kelas" method="GET" class="mb-4 space-y-3">
        <div class="flex gap-2">
            <input type="month" name="bulan" class="flex-1 bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none shadow-sm" value="{{ $bulan ?? '' }}">
            <input type="text" name="search" class="flex-1 bg-white border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-candyBlue outline-none shadow-sm" placeholder="Cari..." value="{{ $search ?? '' }}">
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="submit" class="active-scale flex-1 bg-gray-800 text-white text-xs font-semibold py-2 rounded-lg flex items-center justify-center gap-1 shadow-sm">
                <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Filter
            </button>
            <a href="/kas-kelas" class="active-scale px-4 bg-gray-200 text-gray-700 text-xs font-semibold py-2 rounded-lg flex items-center justify-center shadow-sm">
                Reset
            </a>
            
            <!-- Tombol PDF -->
            <a href="{{ route('kas.pdf') }}" class="active-scale flex-1 bg-red-500 text-white text-xs font-semibold py-2 rounded-lg flex items-center justify-center gap-1 shadow-sm">
                <svg class="w-4 h-4" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.523 12.424c.14-.082.293-.162.459-.238a7.878 7.878 0 0 1-.45.606c-.28.337-.498.516-.635.572a.266.266 0 0 1-.035.012.282.282 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548zm2.455-1.647c-.119.025-.237.05-.356.078a21.148 21.148 0 0 0 .5-1.05 12.045 12.045 0 0 0 .51.858c-.217.032-.436.07-.654.114zm2.525.939a3.881 3.881 0 0 1-.435-.41c.228.005.434.022.612.054.317.057.466.147.518.209a.095.095 0 0 1 .026.064.436.436 0 0 1-.06.2.307.307 0 0 1-.094.124.107.107 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256zM8.278 6.97c-.04.244-.108.524-.2.829a4.86 4.86 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.517.517 0 0 1 .145-.04c.013.03.028.092.032.198.005.122-.007.277-.038.465z"/><path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.651 11.651 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.856.856 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.844.844 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.76 5.76 0 0 0-1.335-.05 10.954 10.954 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.238 1.238 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a19.697 19.697 0 0 1-1.062 2.227 7.662 7.662 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103z"/></svg> PDF
            </a>
            
            <!-- Tombol WA -->
            <a href="https://wa.me/?text={{ $pesanWA }}" target="_blank" class="active-scale flex-1 bg-green-500 text-white text-xs font-semibold py-2 rounded-lg flex items-center justify-center gap-1 shadow-sm">
                <svg class="w-4 h-4" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.005-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg> WA
            </a>
        </div>
    </form>

    <!-- DAFTAR LIST (Gantiin Table) -->
    <div class="space-y-3">
        @forelse($dataKas as $kas)
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <!-- Icon Masuk/Keluar -->
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ (($kas[2] ?? '') == 'Pemasukan' || ($kas[2] ?? '') == 'Uang Masuk') ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500' }}">
                        @if(($kas[2] ?? '') == 'Pemasukan' || ($kas[2] ?? '') == 'Uang Masuk')
                            <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        @else
                            <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        @endif
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="font-bold text-gray-800 text-sm truncate">{{ $kas[3] ?? '-' }}</h4>
                        <p class="text-[11px] text-gray-500">{{ $kas[1] ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="text-right shrink-0 ml-2">
                    <p class="font-bold text-sm {{ (($kas[2] ?? '') == 'Pemasukan' || ($kas[2] ?? '') == 'Uang Masuk') ? 'text-green-600' : 'text-red-600' }}">
                        {{ (($kas[2] ?? '') == 'Pemasukan' || ($kas[2] ?? '') == 'Uang Masuk') ? '+' : '-' }}Rp {{ number_format((int)($kas[4] ?? 0), 0, ',', '.') }}
                    </p>
                    <div class="flex justify-end gap-1 mt-2">
                        <!-- Tombol Edit -->
                        <button type="button" 
                            onclick="bukaModalEditKelas('{{ $kas[0] ?? '' }}', '{{ $kas[1] ?? '' }}', '{{ $kas[2] ?? '' }}', '{{ htmlspecialchars($kas[3] ?? '', ENT_QUOTES) }}', '{{ $kas[4] ?? '' }}', '{{ htmlspecialchars($kas[5] ?? '', ENT_QUOTES) }}')"
                            class="active-scale bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-[10px] font-bold">
                            Edit
                        </button>
                        
                        <!-- Tombol Hapus -->
                        <form action="/hapus-kas-kelas/{{ $kas[0] ?? '' }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="active-scale bg-red-100 text-red-600 px-2 py-1 rounded text-[10px] font-bold">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-xl border border-gray-100 border-dashed">
                <p class="text-gray-400 text-sm">Belum ada data transaksi kas kelas.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- MODAL EDIT KELAS (Murni Tailwind + JS) -->
<div id="modalEditKelas" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl overflow-hidden transform transition-all">
        <div class="bg-yellow-400 px-5 py-3 flex justify-between items-center">
            <h3 class="font-bold text-yellow-900 flex items-center gap-2">
                <span>✏️</span> Edit Transaksi Kelas
            </h3>
            <button onclick="tutupModalEditKelas()" class="text-yellow-900 hover:text-white p-1">
                <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-5">
            <form id="formEditKelas" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal</label>
                        <input type="date" id="editTanggalKelas" name="tanggal" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Jenis</label>
                        <select id="editJenisKelas" name="jenis" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 outline-none" required>
                            <option value="Pemasukan">Uang Masuk</option>
                            <option value="Pengeluaran">Uang Keluar</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">ID Siswa / Nama</label>
                    <input type="text" id="editIdSiswa" name="id_siswa" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Nominal (Rp)</label>
                    <input type="number" id="editNominalKelas" name="nominal" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Keterangan Tambahan</label>
                    <textarea id="editKeteranganKelas" name="keterangan" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 outline-none" required></textarea>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="tutupModalEditKelas()" class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold py-3 rounded-xl shadow-md transition-colors">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Fungsi pembantu untuk decode HTML entities
    function decodeHTML(html) {
        var txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    }

    // Fungsi buka modal khusus Kelas
    function bukaModalEditKelas(id, tanggal, jenis, id_siswa, nominal, keterangan) {
        const modal = document.getElementById('modalEditKelas');
        const form = document.getElementById('formEditKelas');
        
        // Ganti action form sesuai ID baris
        form.action = '/update-kas-kelas/' + id;
        
        // Isi inputan
        document.getElementById('editTanggalKelas').value = tanggal;
        
        // Handle value Jenis (Kalo di DB nyimpennya Uang Masuk, kita sesuaikan ke Pemasukan)
        let setJenis = (jenis === 'Uang Masuk') ? 'Pemasukan' : (jenis === 'Uang Keluar' ? 'Pengeluaran' : jenis);
        document.getElementById('editJenisKelas').value = setJenis;
        
        document.getElementById('editIdSiswa').value = decodeHTML(id_siswa);
        document.getElementById('editNominalKelas').value = nominal;
        document.getElementById('editKeteranganKelas').value = decodeHTML(keterangan);
        
        // Tampilkan modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Fungsi tutup modal
    function tutupModalEditKelas() {
        const modal = document.getElementById('modalEditKelas');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection