@extends('layout')

@section('konten')

<!-- WIDGET ANALITIK SALDO -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm border-0">
            <div class="card-body">
                <h6 class="card-title">📈 Total Pemasukan</h6>
                <h3 class="fw-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger shadow-sm border-0">
            <div class="card-body">
                <h6 class="card-title">📉 Total Pengeluaran</h6>
                <h3 class="fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm border-0">
            <div class="card-body">
                <h6 class="card-title">💼 Sisa Saldo Kas</h6>
                <h3 class="fw-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Kolom Kiri: Form Input -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">🏫 Input Kas Kelas</div>
            <div class="card-body">
                @if(session('sukses'))
                    <div class="alert alert-success fw-bold">{{ session('sukses') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
                @endif

                <form action="/simpan-kas-kelas" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Jenis Transaksi</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Pemasukan">Pemasukan (Uang Masuk)</option>
                            <option value="Pengeluaran">Pengeluaran (Uang Keluar)</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">ID Siswa / Nama</label>
                        <input type="text" name="id_siswa" class="form-control" placeholder="Cth: Budi atau S-001" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="Cth: 25000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Cth: Bayar kas bulan Juli" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">🏫 Simpan Data Kas Kelas</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Tabel, Filter, dan Modal Edit -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <span>📋 Riwayat Kas Kelas</span>
            </div>
            <div class="card-body">
                
                <!-- FITUR FILTER & PENCARIAN -->
                <form action="/kas-kelas" method="GET" class="row g-2 mb-3">
                    <div class="col-auto">
                        <input type="month" name="bulan" class="form-control" value="{{ $bulan ?? '' }}">
                    </div>
                    <div class="col-auto">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama/keterangan..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-secondary">🔍 Filter</button>
                        <a href="/kas-kelas" class="btn btn-outline-danger">Reset</a>

                        <a href="{{ route('kas.pdf') }}" class="btn btn-danger text-white fw-bold d-flex align-items-center gap-2">
                        <!-- Icon PDF SVG -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
                        <path d="M5.523 12.424c.14-.082.293-.162.459-.238a7.878 7.878 0 0 1-.45.606c-.28.337-.498.516-.635.572a.266.266 0 0 1-.035.012.282.282 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548zm2.455-1.647c-.119.025-.237.05-.356.078a21.148 21.148 0 0 0 .5-1.05 12.045 12.045 0 0 0 .51.858c-.217.032-.436.07-.654.114zm2.525.939a3.881 3.881 0 0 1-.435-.41c.228.005.434.022.612.054.317.057.466.147.518.209a.095.095 0 0 1 .026.064.436.436 0 0 1-.06.2.307.307 0 0 1-.094.124.107.107 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256zM8.278 6.97c-.04.244-.108.524-.2.829a4.86 4.86 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.517.517 0 0 1 .145-.04c.013.03.028.092.032.198.005.122-.007.277-.038.465z"/>
                        <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.651 11.651 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.856.856 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.844.844 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.76 5.76 0 0 0-1.335-.05 10.954 10.954 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.238 1.238 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a19.697 19.697 0 0 1-1.062 2.227 7.662 7.662 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103z"/>
                        </svg>
                        Download PDF
                            @php
                           // Bikin format teks WA-nya, %0A itu kode enter (baris baru) di WhatsApp
                            $pesanWA = "Assalamu'alaikum Ibu-ibu, ini update Laporan Kas Kelas kita yaa 📢%0A%0A";
                            $pesanWA .= "💰 Total Pemasukan: Rp " . number_format($totalPemasukan, 0, ',', '.') . "%0A";
                            $pesanWA .= "💸 Total Pengeluaran: Rp " . number_format($totalPengeluaran, 0, ',', '.') . "%0A";
                            $pesanWA .= "💳 Sisa Saldo Kas: Rp " . number_format($saldo, 0, ',', '.') . "%0A%0A";
                        $pesanWA .= "Terima kasih! 🙏";
                    @endphp

                        <!-- Tombol Share WA -->
                            <a href="https://wa.me/?text={{ $pesanWA }}" target="_blank" class="btn btn-success text-white fw-bold d-flex align-items-center gap-2 ms-2">
                        <!-- Icon WA -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.005-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                    </svg>
                    Share WA
                </a>

                    </a>


                    </div>
                </form>

                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Tanggal</th>
                                <th>ID Siswa / Nama</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataKas as $kas)
                                <tr>
                                    <td>{{ $kas[1] ?? '-' }}</td>
                                    <td>{{ $kas[3] ?? '-' }}</td>
                                    <td>
                                        @if(($kas[2] ?? '') == 'Pemasukan' || ($kas[2] ?? '') == 'Uang Masuk')
                                            <span class="badge bg-success">Masuk</span>
                                        @else
                                            <span class="badge bg-danger">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format((int)($kas[4] ?? 0), 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <!-- Tombol Pemicu Modal Edit -->
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModalKelas{{ $loop->index }}">
                                                ✏️ Edit
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <form action="/hapus-kas-kelas/{{ $kas[0] ?? '' }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL EDIT (Bikin Pop-up buat masing-masing baris) -->
                                <div class="modal fade" id="editModalKelas{{ $loop->index }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning">
                                                <h5 class="modal-title fw-bold">✏️ Edit Transaksi Kelas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="/update-kas-kelas/{{ $kas[0] ?? '' }}" method="POST">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label>Tanggal</label>
                                                        <input type="date" name="tanggal" class="form-control" value="{{ $kas[1] ?? '' }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Jenis Transaksi</label>
                                                        <select name="jenis" class="form-select" required>
                                                            <option value="Pemasukan" {{ ($kas[2] ?? '') == 'Pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                                            <option value="Pengeluaran" {{ ($kas[2] ?? '') == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>ID Siswa / Nama</label>
                                                        <input type="text" name="id_siswa" class="form-control" value="{{ $kas[3] ?? '' }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Nominal</label>
                                                        <input type="number" name="nominal" class="form-control" value="{{ $kas[4] ?? '' }}" required>
                                                    </div>
                                                    <div class="mb-2">
                                                        <label>Keterangan</label>
                                                        <textarea name="keterangan" class="form-control" required>{{ $kas[5] ?? '' }}</textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-warning w-100 fw-bold">Update Data Kelas</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END MODAL -->

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data transaksi kas kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection