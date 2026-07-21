<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;

class GSheetController extends Controller
{
    // Fungsi buat nerima data dari form Kas Kelas
    public function simpanKasKelas(Request $request)
    {
        // 1. Setup Koneksi Google Sheets
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        // Pake ID Spreadsheet yang kemaren udah sukses tembus!
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        // 2. Bikin ID Transaksi Otomatis (Format: TRX-TanggalJam)
        $idTransaksi = 'TRX-' . time();
        
        // 3. Susun data sesuai urutan kolom di Sheet lu: 
        // ID_Transaksi | Tanggal | Jenis | ID_Siswa | Nominal | Keterangan
        $values = [
            [
                $idTransaksi, 
                $request->tanggal, 
                $request->jenis, 
                $request->id_siswa, 
                $request->nominal, 
                $request->keterangan
            ]
        ];
        
        $body = new Sheets\ValueRange([
            'values' => $values
        ]);
        
        $params = [
            'valueInputOption' => 'USER_ENTERED' // Biar format angka & tanggal dibaca bener sama Google
        ];
        
        // 4. Tembak ke Tab 'Transaksi_Kas'
        $range = 'Transaksi_Kas!A:F';
        
        try {
            // Eksekusi fungsi 'append' buat nambahin data di baris paling bawah yang kosong
            $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
            
            // Kalau sukses, balik ke halaman tadi bawa pesan sukses
            return redirect()->back()->with('sukses', 'Mantap bro! Data Kas Kelas berhasil masuk ke Spreadsheet.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Waduh gagal: ' . $e->getMessage());
        }
    }
}