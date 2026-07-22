@extends('layout')

@section('konten')
<div class="row">
    <!-- Kolom Kiri: Form Input Pribadi -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white fw-bold">
                💰 Input Kas Pribadi
            </div>
            <div class="card-body">
                
                @if(session('sukses'))
                    <div class="alert alert-success fw-bold">{{ session('sukses') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
                @endif

                <form action="/simpan-kas-pribadi" method="POST">
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
                        <label class="form-label fw-bold">Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Cth: Makan, Bensin, Nabung, Gaji" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="Cth: 25000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Cth: Nasi padang lauk rendang" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold">💸 Simpan Data Pribadi</button>
                </form>
            </div>
        </div>
    </div>

<!-- Kolom Kanan: Tabel Data Pribadi -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white fw-bold">
                📋 Riwayat Kas Pribadi
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px;">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Aksi</th> <!-- Tambahan Judul Kolom -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPribadi as $kas)
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
                                    
                                    <!-- Bagian Tombol Hapus Pindah ke Sini -->
                                    <td>
                                        <form action="/hapus-kas-pribadi/{{ $kas[0] ?? $loop->index }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini bro?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <!-- colspan diubah jadi 5 karena kolomnya sekarang ada 5 -->
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data transaksi pribadi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>