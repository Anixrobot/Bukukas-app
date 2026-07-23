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
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        $idTransaksi = 'TRX-' . time();
        
        $values = [[$idTransaksi, $request->tanggal, $request->jenis, $request->id_siswa, $request->nominal, $request->keterangan]];
        $body = new Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        
        try {
            $service->spreadsheets_values->append($spreadsheetId, 'Transaksi_Kas!A:F', $body, $params);
            return redirect()->back()->with('sukses', 'Data Kas Kelas berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function indexKasKelas(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Kas!A2:F');
            $allData = $response->getValues() ?? []; 

            $responseSiswa = $service->spreadsheets_values->get($spreadsheetId, 'Siswa!A2:C');
            $allSiswa = $responseSiswa->getValues() ?? [];
            
            $totalPemasukan = 0;
            $totalPengeluaran = 0;
            $filteredData = [];
            $sudahBayarBulanIni = []; 

            $search = $request->query('search');
            $bulan = $request->query('bulan');
            $bulanAktif = $bulan ?: date('Y-m'); 

            foreach ($allData as $row) {
                $row = array_pad($row, 6, ''); 
                $tanggal = $row[1];
                $jenis = $row[2];
                $idSiswa = $row[3];
                $nominal = (int)$row[4];
                $keterangan = strtolower($row[5]);

                $matchSearch = !$search || strpos($keterangan, strtolower($search)) !== false || strpos(strtolower($idSiswa), strtolower($search)) !== false;
                $matchBulan = !$bulan || strpos($tanggal, $bulan) === 0;

                if (strpos($tanggal, $bulanAktif) === 0 && ($jenis == 'Pemasukan' || $jenis == 'Uang Masuk')) {
                    $sudahBayarBulanIni[] = strtolower(trim($idSiswa));
                }

                if ($matchSearch && $matchBulan) {
                    $filteredData[] = $row;
                    if ($jenis == 'Pemasukan' || $jenis == 'Uang Masuk') $totalPemasukan += $nominal;
                    if ($jenis == 'Pengeluaran' || $jenis == 'Uang Keluar') $totalPengeluaran += $nominal;
                }
            }

            $saldo = $totalPemasukan - $totalPengeluaran;
            $dataKas = array_reverse($filteredData);

            $belumBayar = [];
            foreach ($allSiswa as $siswa) {
                $siswa = array_pad($siswa, 3, '');
                $idS = strtolower(trim($siswa[0])); 
                $namaS = strtolower(trim($siswa[1])); 
                $namaAsli = $siswa[1];

                if ($namaAsli != '' && !in_array($idS, $sudahBayarBulanIni) && !in_array($namaS, $sudahBayarBulanIni)) {
                    $belumBayar[] = $namaAsli;
                }
            }

            return view('dashboard-kelas', compact('dataKas', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'search', 'bulan', 'belumBayar', 'bulanAktif'));
        } catch (\Exception $e) {
            return view('dashboard-kelas', ['dataKas' => [], 'totalPemasukan'=>0, 'totalPengeluaran'=>0, 'saldo'=>0, 'belumBayar'=>[]])->with('error', 'Gagal memuat data dari sistem: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DIMENSI 2: KAS KELAS (UPDATE & DELETE)
    // ==========================================
    public function updateKasKelas(Request $request, $id)
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
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
                            $rowIndexToUpdate = $index + 1; 
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

                return redirect()->back()->with('sukses', 'Data Kas Kelas berhasil diperbarui.');
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function hapusKasKelas($id)
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
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
                return redirect()->back()->with('sukses', 'Data Kas Kelas berhasil dihapus.');
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DIMENSI 3: KAS PRIBADI (CREATE & READ + FILTER/SALDO)
    // ==========================================
    public function simpanKasPribadi(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        $idTransaksi = 'PRB-' . time(); 
        
        $values = [[$idTransaksi, $request->tanggal, $request->jenis, $request->kategori, $request->nominal, $request->keterangan]];
        $body = new Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        
        try {
            $service->spreadsheets_values->append($spreadsheetId, 'Transaksi_Pribadi!A:F', $body, $params);
            return redirect()->back()->with('sukses', 'Data Kas Pribadi berhasil dicatat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function indexKasPribadi(Request $request)
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
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
            $bulan = $request->query('bulan'); 

            foreach ($allData as $row) {
                $row = array_pad($row, 6, ''); 
                $tanggal = $row[1];
                $jenis = $row[2];
                $kategori = strtolower($row[3]);
                $nominal = (int)$row[4];
                $keterangan = strtolower($row[5]);

                $matchSearch = !$search || strpos($kategori, strtolower($search)) !== false || strpos($keterangan, strtolower($search)) !== false;
                $matchBulan = !$bulan || strpos($tanggal, $bulan) === 0;

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
            return view('dashboard-pribadi', ['dataPribadi' => [], 'totalPemasukan'=>0, 'totalPengeluaran'=>0, 'saldo'=>0])->with('error', 'Gagal memuat data dari sistem: ' . $e->getMessage());
        }
    }

    // ==========================================
    // DIMENSI 4: KAS PRIBADI (UPDATE & DELETE)
    // ==========================================
    public function updateKasPribadi(Request $request, $id)
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
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
                            $rowIndexToUpdate = $index + 1; 
                            break;
                        }
                    }
                }
            }

            if ($rowIndexToUpdate != -1) {
                $updateRange = "Transaksi_Pribadi!A{$rowIndexToUpdate}:F{$rowIndexToUpdate}";
                $updateValues = [[$id, $request->tanggal, $request->jenis, $request->kategori, $request->nominal, $request->keterangan]];
                
                $body = new Sheets\ValueRange(['values' => $updateValues]);
                $params = ['valueInputOption' => 'USER_ENTERED'];
                $service->spreadsheets_values->update($spreadsheetId, $updateRange, $body, $params);

                return redirect()->back()->with('sukses', 'Data Kas Pribadi berhasil diperbarui.');
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function hapusKasPribadi($id)
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
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
                return redirect()->back()->with('sukses', 'Data Kas Pribadi berhasil dihapus.');
            }
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function downloadPDF()
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Transaksi_Kas!A2:F');
            $allData = $response->getValues() ?? []; 
            $allData = array_reverse($allData);

            $dataKas = [];
            foreach ($allData as $row) {
                $row = array_pad($row, 6, ''); 
                $dataKas[] = [
                    'Tanggal'    => $row[1],
                    'Jenis'      => $row[2],
                    'ID_Siswa'   => $row[3],
                    'Nominal'    => $row[4],
                    'Keterangan' => $row[5]
                ];
            }

            $data = [
                'title' => 'Laporan Kas Kelas',
                'tanggal' => date('d/m/Y'),
                'kas' => $dataKas 
            ];

            $pdf = Pdf::loadView('laporan-pdf', $data);
            return $pdf->download('Laporan_Kas_Kelas_'.date('Y-m-d').'.pdf');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat dokumen PDF: ' . $e->getMessage());
        }
    }

    public function downloadPDFPribadi()
    {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
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
                    'Kategori'   => $row[3],
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
            return redirect()->back()->with('error', 'Gagal membuat dokumen PDF: ' . $e->getMessage());
        }
    }
// ==========================================
    // DIMENSI 5: DATA SISWA (CREATE & READ)
    // ==========================================
    public function indexSiswa(Request $request)
    {
        $client = new \Google\Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
        $client->addScope(\Google\Service\Sheets::SPREADSHEETS);
        $service = new \Google\Service\Sheets($client);
        
        // Ganti dengan Spreadsheet ID Anda
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Siswa!A2:C');
            $dataSiswa = $response->getValues() ?? []; 
            
            // Membalik urutan agar data terbaru muncul di atas
            $dataSiswa = array_reverse($dataSiswa);

            return view('dashboard-siswa', compact('dataSiswa'));
        } catch (\Exception $e) {
            return view('dashboard-siswa', ['dataSiswa' => []])->with('error', 'Gagal memuat data siswa: ' . $e->getMessage());
        }
    }

    public function simpanSiswa(Request $request)
    {
        // Validasi input dasar
        $request->validate([
            'id_siswa' => 'required',
            'nama' => 'required',
        ]);

        $client = new \Google\Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
        $client->addScope(\Google\Service\Sheets::SPREADSHEETS);
        $service = new \Google\Service\Sheets($client);
        
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        // Format susunan data: ID_Siswa, Nama, NIS
        $values = [[$request->id_siswa, $request->nama, $request->nis]];
        $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'USER_ENTERED'];
        
        try {
            $service->spreadsheets_values->append($spreadsheetId, 'Siswa!A:C', $body, $params);
            return redirect()->back()->with('sukses', 'Data Siswa berhasil disimpan ke dalam sistem.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Proses penyimpanan data gagal: ' . $e->getMessage());
        }
    }

    public function hapusSiswa($id)
    {
        $client = new \Google\Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
        $client->addScope(\Google\Service\Sheets::SPREADSHEETS);
        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
        
        try {
            // Cari baris yang ID Siswa-nya cocok
            $response = $service->spreadsheets_values->get($spreadsheetId, 'Siswa!A:A');
            $values = $response->getValues() ?? [];
            $rowIndexToDelete = -1;

            foreach ($values as $index => $row) {
                if (isset($row[0])) {
                    $idExcel = trim((string)$row[0]);
                    $idDicari = trim((string)$id);
                    if ($idExcel !== '' && $idExcel === $idDicari) {
                        $rowIndexToDelete = $index;
                        break;
                    }
                }
            }

            if ($rowIndexToDelete != -1) {
                // Ambil sheetId numerik untuk sheet "Siswa" secara dinamis
                $spreadsheet = $service->spreadsheets->get($spreadsheetId);
                $siswaSheetId = null;
                foreach ($spreadsheet->getSheets() as $sheet) {
                    if ($sheet->getProperties()->getTitle() === 'Siswa') {
                        $siswaSheetId = $sheet->getProperties()->getSheetId();
                        break;
                    }
                }

                if ($siswaSheetId === null) {
                    return redirect()->back()->with('error', 'Sheet "Siswa" tidak ditemukan.');
                }

                $requests = [
                    new \Google\Service\Sheets\Request([
                        'deleteDimension' => [
                            'range' => [
                                'sheetId' => $siswaSheetId,
                                'dimension' => 'ROWS',
                                'startIndex' => $rowIndexToDelete,
                                'endIndex' => $rowIndexToDelete + 1
                            ]
                        ]
                    ])
                ];
                $service->spreadsheets->batchUpdate(
                    $spreadsheetId,
                    new \Google\Service\Sheets\BatchUpdateSpreadsheetRequest(['requests' => $requests])
                );
                return redirect()->back()->with('sukses', 'Data Siswa berhasil dihapus dari sistem.');
            }
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan untuk dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}