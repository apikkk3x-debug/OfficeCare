<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kerusakan - OfficeCare</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            color: #333; 
            margin: 30px; /* Menambah jarak pinggir halaman kertas */
            font-size: 12px; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 35px; 
        }
        .header h2 { 
            margin: 0; 
            font-size: 16px; 
            text-transform: uppercase; 
        }
        .header p { 
            margin: 4px 0 0; 
            font-size: 11px; 
            color: #666; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            font-size: 12px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px 10px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        
        /* Area Tanda Tangan: Diberi jarak jauh dari tabel dan digeser agak ke tengah */
        .footer-print { 
            margin-top: 60px; /* Jarak atas lebih renggang dari tabel */
            display: flex; 
            justify-content: flex-end; 
        }
        .sign-container { 
            text-align: right; 
            font-size: 12px; 
            margin-right: 40px; /* Menggeser area tanda tangan agar tidak terlalu ke pinggir kertas */
        }
        .sign-container p {
            margin: 2px 0;
            font-size: 12px;
        }
        .sign-space { 
            height: 65px; /* Ruang kosong untuk tanda tangan basah */
        }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.history.back()" style="padding: 6px 12px; cursor: pointer; font-size: 12px;">&larr; Kembali</button>
    </div>

    <div class="header">
        <h2>Laporan Rekapitulasi Kerusakan Fasilitas Kantor</h2>
        <p>Aplikasi OfficeCare - Sistem Manajemen Sarana dan Prasarana</p>
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
            <p><b>Admin Sarpras</b></p>
        </div>
    </div>

</body>
</html>