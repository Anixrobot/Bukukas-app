@extends('layout')

@section('konten')
<div class="row">
    <!-- Kolom Kiri: Form Input Kas Kelas -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">
                🏫 Input Kas Kelas
            </div>
            <div class="card-body">
                
                @if(session('sukses'))
                    <div class="alert alert-success fw-bold">{{ session('sukses') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
                @endif

                <form action="/simpan-kas-kelas" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Transaksi</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Pemasukan">Pemasukan (Duit Masuk)</option>
                            <option value="Pengeluaran">Pengeluaran (Duit Keluar)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ID Siswa</label>
                        <input type="text" name="id_siswa" class="form-control" placeholder="Cth: Nama siswa atau ID siswa" required>
                    </div>

                    <div class="mb-3">
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

    <!-- Kolom Kanan: Tabel Data Kas Kelas -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">
                📋 Riwayat Kas Kelas
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Tanggal</th>
                                <th>ID Siswa</th>
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
                                        @if(($kas[2] ?? '') == 'Pemasukan')
                                            <span class="badge bg-success">Masuk</span>
                                        @else
                                            <span class="badge bg-danger">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format((int)($kas[4] ?? 0), 0, ',', '.') }}</td>
                                    
                                    <td>
                                        <form action="/hapus-kas-kelas/{{ $kas[0] ?? $loop->index }}" method="POST" onsubmit="return confirm('Yakin mau hapus data kas kelas ini bro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
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