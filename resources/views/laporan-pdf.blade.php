<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kas Kelas</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, p { text-align: center; margin: 5px 0; }
    </style>
</head>
<body>
    <h2>Laporan Kas Kelas</h2>
    <p>Dicetak pada: {{ $tanggal }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama / ID</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kas as $item)
            <tr>
                <!-- Sesuaikan nama index array ini dengan nama kolom di Google Sheet lu -->
                <td>{{ $item['Tanggal'] ?? '-' }}</td>
                <td>{{ $item['ID_Siswa'] ?? '-' }}</td>
                <td>{{ $item['Jenis'] ?? '-' }}</td>
                <td>Rp {{ number_format((int)$item['Nominal'], 0, ',', '.') }}</td>
                <td>{{ $item['Keterangan'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>