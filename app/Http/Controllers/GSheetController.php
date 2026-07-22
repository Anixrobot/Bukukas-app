<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;

class GSheetController extends Controller
{
    // ==========================================
    // DIMENSI 1: KAS KELAS (CREATE & READ + FILTER/SALDO)
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

    public function indexKasKelas(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Kas!A2:F');
            $allData = $response->getValues() ?? []; 
            
            $totalPemasukan = 0;
            $totalPengeluaran = 0;
            $filteredData = [];

            $search = $request->query('search');
            $bulan = $request->query('bulan');

            foreach ($allData as $row) {
                $row = array_pad($row, 6, ''); // Antisipasi cell kosong
                $tanggal = $row[1];
                $jenis = $row[2];
                $nominal = (int)$row[4];
                $keterangan = strtolower($row[5]);

                $matchSearch = !$search || strpos($keterangan, strtolower($search)) !== false;
                $matchBulan = !$bulan || strpos($tanggal, $bulan) === 0;

                if ($matchSearch && $matchBulan) {
                    $filteredData[] = $row;
                    if ($jenis == 'Pemasukan' || $jenis == 'Uang Masuk') $totalPemasukan += $nominal;
                    if ($jenis == 'Pengeluaran' || $jenis == 'Uang Keluar') $totalPengeluaran += $nominal;
                }
            }

            $saldo = $totalPemasukan - $totalPengeluaran;
            $dataKas = array_reverse($filteredData);

            return view('dashboard-kelas', compact('dataKas', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'search', 'bulan'));
        } catch (\Exception $e) {
            return view('dashboard-kelas', ['dataKas' => [], 'totalPemasukan'=>0, 'totalPengeluaran'=>0, 'saldo'=>0])->with('error', 'Gagal narik data: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DIMENSI 2: KAS KELAS (UPDATE & DELETE)
    // ==========================================
    public function updateKasKelas(Request $request, $id)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);

        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        $range = 'Transaksi_Kas!A:A'; 

        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues() ?? [];
            $rowIndexToUpdate = -1;

            if (!empty($values)) {
                foreach ($values as $index => $row) {
                    if (isset($row[0])) {
                        $idExcel = preg_replace('/[^A-Za-z0-9\-]/', '', (string)$row[0]);
                        $idDicari = preg_replace('/[^A-Za-z0-9\-]/', '', (string)$id);
                        if ($idExcel !== '' && ($idExcel === $idDicari || strpos($idDicari, $idExcel) !== false)) {
                            $rowIndexToUpdate = $index + 1; // 1-based index buat Google Sheets
                            break;
                        }
                    }
                }
            }

            if ($rowIndexToUpdate != -1) {
                $updateRange = "Transaksi_Kas!A{$rowIndexToUpdate}:F{$rowIndexToUpdate}";
                $updateValues = [[$id, $request->tanggal, $request->jenis, $request->id_siswa, $request->nominal, $request->keterangan]];
                
                $body = new Sheets\ValueRange(['values' => $updateValues]);
                $params = ['valueInputOption' => 'USER_ENTERED'];
                $service->spreadsheets_values->update($spreadsheetId, $updateRange, $body, $params);

                return redirect()->back()->with('sukses', 'Mantap bro, data kas kelas berhasil diedit!');
            }
            return redirect()->back()->with('error', 'Waduh, data ID gak ketemu buat diedit.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal ngedit data nih: ' . $e->getMessage());
        }
    }

    public function hapusKasKelas($id)
    {
        // (Isi fungsi hapusKasKelas persis kayak sebelumnya, pakai regex preg_replace)
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Kas!A:A');
            $values = $response->getValues() ?? [];
            $rowIndexToDelete = -1;
            foreach ($values as $index => $row) {
                if (isset($row[0])) {
                    $idExcel = preg_replace('/[^A-Za-z0-9\-]/', '', (string)$row[0]);
                    $idDicari = preg_replace('/[^A-Za-z0-9\-]/', '', (string)$id);
                    if ($idExcel !== '' && ($idExcel === $idDicari || strpos($idDicari, $idExcel) !== false)) {
                        $rowIndexToDelete = $index; break;
                    }
                }
            }
            if ($rowIndexToDelete != -1) {
                $requests = [new Sheets\Request(['deleteDimension' => ['range' => ['sheetId' => 1856444649, 'dimension' => 'ROWS', 'startIndex' => $rowIndexToDelete, 'endIndex' => $rowIndexToDelete + 1]]])];
                $service->spreadsheets->batchUpdate($spreadsheetId, new Sheets\BatchUpdateSpreadsheetRequest(['requests' => $requests]));
                return redirect()->back()->with('sukses', 'Mantap bro, data kas kelas berhasil dihapus!');
            }
            return redirect()->back()->with('error', 'Waduh, data ID gak ketemu.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DIMENSI 3: KAS PRIBADI (CREATE & READ + FILTER/SALDO)
    // ==========================================
    public function simpanKasPribadi(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        $idTransaksi = 'PRB-' . time(); 
        
        $values = [[$idTransaksi, $request->tanggal, $request->jenis, $request->kategori, $request->nominal, $request->keterangan]];
        $body = new Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        
        try {
            $service->spreadsheets_values->append($spreadsheetId, 'Transaksi_Pribadi!A:F', $body, $params);
            return redirect()->back()->with('sukses', 'Sip bro! Keuangan pribadi lu udah tercatat aman.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal nyimpen: ' . $e->getMessage());
        }
    }

    public function indexKasPribadi(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Pribadi!A2:F');
            $allData = $response->getValues() ?? []; 
            
            $totalPemasukan = 0;
            $totalPengeluaran = 0;
            $filteredData = [];

            $search = $request->query('search');
            $bulan = $request->query('bulan'); // Format YYYY-MM

            foreach ($allData as $row) {
                $row = array_pad($row, 6, ''); // Mencegah error kalau ada cell kosong
                $tanggal = $row[1];
                $jenis = $row[2];
                $kategori = strtolower($row[3]);
                $nominal = (int)$row[4];
                $keterangan = strtolower($row[5]);

                // Logika Filter
                $matchSearch = !$search || strpos($kategori, strtolower($search)) !== false || strpos($keterangan, strtolower($search)) !== false;
                $matchBulan = !$bulan || strpos($tanggal, $bulan) === 0;

                // Hitung Saldo cuma dari data yang lolos filter
                if ($matchSearch && $matchBulan) {
                    $filteredData[] = $row;
                    if ($jenis == 'Pemasukan') $totalPemasukan += $nominal;
                    if ($jenis == 'Pengeluaran') $totalPengeluaran += $nominal;
                }
            }

            $saldo = $totalPemasukan - $totalPengeluaran;
            $dataPribadi = array_reverse($filteredData);

            return view('dashboard-pribadi', compact('dataPribadi', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'search', 'bulan'));
        } catch (\Exception $e) {
            return view('dashboard-pribadi', ['dataPribadi' => [], 'totalPemasukan'=>0, 'totalPengeluaran'=>0, 'saldo'=>0])->with('error', 'Gagal narik data: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DIMENSI 4: KAS PRIBADI (UPDATE & DELETE)
    // ==========================================
    public function updateKasPribadi(Request $request, $id)
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);

        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        $range = 'Transaksi_Pribadi!A:A'; 

        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues() ?? [];
            $rowIndexToUpdate = -1;

            if (!empty($values)) {
                foreach ($values as $index => $row) {
                    if (isset($row[0])) {
                        $idExcel = preg_replace('/[^A-Za-z0-9\-]/', '', (string)$row[0]);
                        $idDicari = preg_replace('/[^A-Za-z0-9\-]/', '', (string)$id);
                        if ($idExcel !== '' && ($idExcel === $idDicari || strpos($idDicari, $idExcel) !== false)) {
                            $rowIndexToUpdate = $index + 1; // 1-based index buat Google Sheets
                            break;
                        }
                    }
                }
            }

            if ($rowIndexToUpdate != -1) {
                // Tembak langsung ke baris yang spesifik (Misal A5:F5)
                $updateRange = "Transaksi_Pribadi!A{$rowIndexToUpdate}:F{$rowIndexToUpdate}";
                $updateValues = [[$id, $request->tanggal, $request->jenis, $request->kategori, $request->nominal, $request->keterangan]];
                
                $body = new Sheets\ValueRange(['values' => $updateValues]);
                $params = ['valueInputOption' => 'USER_ENTERED'];
                $service->spreadsheets_values->update($spreadsheetId, $updateRange, $body, $params);

                return redirect()->back()->with('sukses', 'Mantap bro, data kas pribadi berhasil diedit!');
            }
            return redirect()->back()->with('error', 'Waduh, data ID gak ketemu buat diedit.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal ngedit data nih: ' . $e->getMessage());
        }
    }

    public function hapusKasPribadi($id)
    {
        // (Isi fungsi hapusKasPribadi persis kayak sebelumnya, pakai regex preg_replace)
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Pribadi!A:A');
            $values = $response->getValues() ?? [];
            $rowIndexToDelete = -1;
            foreach ($values as $index => $row) {
                if (isset($row[0])) {
                    $idExcel = preg_replace('/[^A-Za-z0-9\-]/', '', (string)$row[0]);
                    $idDicari = preg_replace('/[^A-Za-z0-9\-]/', '', (string)$id);
                    if ($idExcel !== '' && ($idExcel === $idDicari || strpos($idDicari, $idExcel) !== false)) {
                        $rowIndexToDelete = $index; break;
                    }
                }
            }
            if ($rowIndexToDelete != -1) {
                $requests = [new Sheets\Request(['deleteDimension' => ['range' => ['sheetId' => 1464599245, 'dimension' => 'ROWS', 'startIndex' => $rowIndexToDelete, 'endIndex' => $rowIndexToDelete + 1]]])];
                $service->spreadsheets->batchUpdate($spreadsheetId, new Sheets\BatchUpdateSpreadsheetRequest(['requests' => $requests]));
                return redirect()->back()->with('sukses', 'Mantap bro, data kas pribadi berhasil dihapus!');
            }
            return redirect()->back()->with('error', 'Waduh, data ID gak ketemu.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

        public function downloadPDF()
    {
        // 1. Panggil koneksi ke Google Sheet (Sama persis kayak indexKasKelas)
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            // 2. Tarik data dari GSheet Transaksi Kas
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Kas!A2:F');
            $allData = $response->getValues() ?? []; 
            
            // Kita balik datanya biar transaksi paling baru ada di urutan paling atas
            $allData = array_reverse($allData);

            // 3. Kita ubah format datanya biar gampang dibaca sama file PDF lu
            $dataKas = [];
            foreach ($allData as $row) {
                $row = array_pad($row, 6, ''); // Antisipasi kalau ada kolom/cell yang kosong di Excel
                $dataKas[] = [
                    'Tanggal'    => $row[1],
                    'Jenis'      => $row[2],
                    'ID_Siswa'   => $row[3],
                    'Nominal'    => $row[4],
                    'Keterangan' => $row[5]
                ];
            }

            // 4. Siapin bungkusan data yang mau dilempar ke file template PDF
            $data = [
                'title' => 'Laporan Kas Kelas',
                'tanggal' => date('d/m/Y'),
                'kas' => $dataKas 
            ];

            // 5. Proses merubah file laporan-pdf.blade.php jadi PDF beneran
            $pdf = Pdf::loadView('laporan-pdf', $data);

            // 6. Langsung download filenya dengan nama yang cakep
            return $pdf->download('Laporan_Kas_Kelas_'.date('Y-m-d').'.pdf');

        } catch (\Exception $e) {
            // Kalau misal internet putus atau error GSheet, kita balikin ke halaman dengan pesan error
            return redirect()->back()->with('error', 'Gagal bikin PDF bro: ' . $e->getMessage());
        }
    }

    public function downloadPDFPribadi()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-credentials.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Pribadi!A2:F');
            $allData = $response->getValues() ?? []; 
            $allData = array_reverse($allData);

            $dataPribadi = [];
            foreach ($allData as $row) {
                $row = array_pad($row, 6, ''); 
                $dataPribadi[] = [
                    'Tanggal'    => $row[1],
                    'Jenis'      => $row[2],
                    'Kategori'   => $row[3], // Kalau di kas kelas namanya ID Siswa, di sini Kategori
                    'Nominal'    => $row[4],
                    'Keterangan' => $row[5]
                ];
            }

            $data = [
                'title' => 'Laporan Kas Pribadi',
                'tanggal' => date('d/m/Y'),
                'kas' => $dataPribadi 
            ];

            $pdf = Pdf::loadView('laporan-pribadi-pdf', $data);
            return $pdf->download('Laporan_Kas_Pribadi_'.date('Y-m-d').'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal bikin PDF bro: ' . $e->getMessage());
        }
    }
}