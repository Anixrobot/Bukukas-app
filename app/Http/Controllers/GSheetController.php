<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;

class GSheetController extends Controller
{
    // ==========================================
    // DIMENSI 1: KAS KELAS (YANG UDAH JALAN)
    // ==========================================
    public function simpanKasKelas(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        $idTransaksi = 'TRX-' . time();
        
        $values = [[$idTransaksi, $request->tanggal, $request->jenis, $request->id_siswa, $request->nominal, $request->keterangan]];
        $body = new Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        
        try {
            $service->spreadsheets_values->append($spreadsheetId, 'Transaksi_Kas!A:F', $body, $params);
            return redirect()->back()->with('sukses', 'Mantap bro! Data Kas Kelas berhasil masuk ke Spreadsheet.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Waduh gagal: ' . $e->getMessage());
        }
    }

    public function indexKasKelas()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Kas!A2:F');
            $dataKas = $response->getValues() ?? []; 
            $dataKas = array_reverse($dataKas);
            return view('dashboard-kelas', compact('dataKas'));
        } catch (\Exception $e) {
            return view('dashboard-kelas', ['dataKas' => []])->with('error', 'Gagal narik data: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DIMENSI 2: KAS PRIBADI (FITUR BARU)
    // ==========================================
    public function simpanKasPribadi(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        $idTransaksi = 'PRB-' . time(); // Kodenya kita bedain dikit buat pribadi
        
        // Urutan: ID_Transaksi | Tanggal | Jenis | Kategori | Nominal | Keterangan
        $values = [[$idTransaksi, $request->tanggal, $request->jenis, $request->kategori, $request->nominal, $request->keterangan]];
        $body = new Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        
        try {
            // Nembak ke tab Transaksi_Pribadi
            $service->spreadsheets_values->append($spreadsheetId, 'Transaksi_Pribadi!A:F', $body, $params);
            return redirect()->back()->with('sukses', 'Sip bro! Keuangan pribadi lu udah tercatat aman.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal nyimpen: ' . $e->getMessage());
        }
    }

    public function indexKasPribadi()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Pribadi!A2:F');
            $dataPribadi = $response->getValues() ?? []; 
            $dataPribadi = array_reverse($dataPribadi);
            return view('dashboard-pribadi', compact('dataPribadi'));
        } catch (\Exception $e) {
            return view('dashboard-pribadi', ['dataPribadi' => []])->with('error', 'Gagal narik data: ' . $e->getMessage());
        }
    }
}