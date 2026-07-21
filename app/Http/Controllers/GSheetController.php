<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;

class GSheetController extends Controller
{
    public function testKoneksi()
    {
        try {
            // 1. Setup Kunci Rahasia
            $client = new Client();
            // Pastikan nama file JSON di bawah ini sama dengan yang lu taruh di storage/app
            $client->setAuthConfig(storage_path('app/google-credentials.json'));
            $client->addScope(Sheets::SPREADSHEETS);

            // 2. Siapin Service Sheets
            $service = new Sheets($client);
            
            // 3. Ambil ID dari file .env lu
            $spreadsheetId = '1udi_WkEsfL_DqnSzxjbH8-2kBBs2eFEfCZKwmWR1ASQ';
            
            // 4. Kita coba narik data dari "Sheet1" (A1 sampai B2)
            $range = 'Sheet1!A1:B2'; 
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            
            return response()->json([
                'status' => 'SUKSES BRO!',
                'pesan' => 'Koneksi ke Google Sheets berhasil mantap.',
                'data_sheet' => $response->getValues()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'GAGAL',
                'pesan' => $e->getMessage()
            ]);
        }
    }
}