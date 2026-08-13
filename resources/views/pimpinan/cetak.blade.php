<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Laporan - Pimpinan OfficeCare</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 30px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 35px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 4px 0 0; font-size: 11px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .footer-print { margin-top: 60px; display: flex; justify-content: flex-end; }
        .sign-container { text-align: right; font-size: 12px; margin-right: 40px; }
        .sign-container p { margin: 2px 0; font-size: 12px; }
        .sign-space { height: 65px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <a href="{{ route('pimpinan.dashboard') }}" style="display: inline-block; padding: 6px 12px; background: #f1f5f9; color: #334155; text-decoration: none; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; cursor: pointer;">
            &larr; Kembali ke Dashboard
        </a>
    </div>
    <div class="header">
        <h2>Rekapitulasi Laporan Kerusakan Fasilitas Kantor</h2>
        <p>Aplikasi OfficeCare - Laporan Resmi Manajemen Pimpinan</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelapor</th>
                <th>Nama Barang</th>
                <th>Lokasi</th>
                <th>Kerusakan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $index => $lap)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $lap->created_at->format('d/m/Y') }}</td>
                    <td>{{ $lap->user->name ?? '-' }}</td>
                    <td>{{ $lap->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $lap->barang->lokasi ?? '-' }}</td>
                    <td>{{ $lap->deskripsi_kerusakan }}</td>
                    <td>{{ $lap->status_laporan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-print">
        <div class="sign-container">
            <p>Pekanbaru, {{ date('d F Y') }}</p>
            <p>Mengetahui,</p>
            <div class="sign-space"></div>
            <p><b>Pimpinan / Manager Sarpras</b></p>
        </div>
    </div>

</body>
</html>