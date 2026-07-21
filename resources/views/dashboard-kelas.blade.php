@extends('layout')

@section('konten')
<div class="row">
    <!-- Kolom Kiri: Form Input -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">
                ➕ Input Transaksi Kas Kelas
            </div>
            <div class="card-body">
                
                <!-- Notifikasi kalau sukses/gagal -->
                @if(session('sukses'))
                    <div class="alert alert-success fw-bold">{{ session('sukses') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
                @endif

                <!-- Form Input -->
                <form action="/simpan-kas-kelas" method="POST">
                    @csrf <!-- Wajib ada di Laravel biar form aman dari hacker -->
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Transaksi</label>
                        <select name="jenis" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Uang Masuk">Uang Masuk (Bayar Kas)</option>
                            <option value="Uang Keluar">Uang Keluar (Beli Keperluan)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ID / Nama Siswa</label>
                        <input type="text" name="id_siswa" class="form-control" placeholder="Cth: Budi atau S-001" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="Cth: 15000" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Cth: Bayar kas minggu ke-1" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">🚀 Simpan Data</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Nanti buat nampilin tabel/rekap -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 bg-light text-center h-100">
            <div class="card-body d-flex flex-column justify-content-center align-items-center text-muted">
                <h1 class="display-4">📊</h1>
                <h4>Tabel Data Kas</h4>
                <p>Nanti rekap data dari Spreadsheet bakal kita tarik dan tampilin di sini bro.</p>
            </div>
        </div>
    </div>
</div>
@endsection