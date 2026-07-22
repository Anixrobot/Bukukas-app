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