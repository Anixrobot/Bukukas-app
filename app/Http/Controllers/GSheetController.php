<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;

class GSheetController extends Controller
{ // <--- Ini nih tanda kurung kurawal yang tadi ilang bro

    // 1. FUNGSI BUAT NERIMA DAN SIMPAN DATA KAS KELAS
    public function simpanKasKelas(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        $idTransaksi = 'TRX-' . time();
        
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
            'valueInputOption' => 'USER_ENTERED'
        ];
        
        $range = 'Transaksi_Kas!A:F';
        
        try {
            $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
            return redirect()->back()->with('sukses', 'Mantap bro! Data Kas Kelas berhasil masuk ke Spreadsheet.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Waduh gagal: ' . $e->getMessage());
        }
    }

    // 2. FUNGSI BUAT NARIK DATA DAN NAMPILIN TABEL KAS KELAS
    public function indexKasKelas()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        // Kita baca dari baris ke-2 (A2)
        $range = 'Transaksi_Kas!A2:F'; 
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $dataKas = $response->getValues() ?? []; 
            
            $dataKas = array_reverse($dataKas);
            
            return view('dashboard-kelas', compact('dataKas'));
        } catch (\Exception $e) {
            return view('dashboard-kelas', ['dataKas' => []])->with('error', 'Gagal narik data: ' . $e->getMessage());
        }
    }
}