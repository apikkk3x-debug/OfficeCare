<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Kerusakan Fasilitas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            margin: 25px;
            line-height: 1.4;
        }

        /* Styling khusus untuk menyembunyikan tombol saat dicetak */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 0 0 6px 0;
            font-size: 11pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 9pt;
            color: #333;
        }

        /* Styling Tabel dengan Garis Gelap Tegas agar Terlihat di Nitro PDF */
        table {
            width: 100%;
            border-collapse: collapse !important;
            margin-top: 15px;
            margin-bottom: 30px;
        }
        th, td {
            border: 1.5px solid #333 !important; /* Warna garis lebih gelap dan solid */
            padding: 8px 10px;
            font-size: 9.5pt;
            vertical-align: middle;
            text-align: left;
            color: #000;
        }
        th {
            background-color: #cbd5e1 !important; /* Warna latar header lebih kontras */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
        }

        /* Kelas Perataan */
        .text-center { text-align: center; }

        .footer {
            float: right;
            text-align: center;
            width: 240px;
            page-break-inside: avoid;
            margin-top: 20px;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Tombol Kembali (Hanya tampil di layar, otomatis hilang saat dicetak) -->
    <div class="no-print" style="margin-bottom: 20px;">
        <a href="{{ route('pimpinan.rekap') }}" style="display: inline-block; padding: 6px 14px; background-color: #4f46e5; color: #fff; text-decoration: none; border-radius: 6px; font-size: 10pt; font-weight: bold;">
            &larr; Kembali ke Rekap
        </a>
    </div>

    <div class="header">
        <h2>OfficeCare - Manajemen Sarana Prasarana Kantor</h2>
        <h3>Laporan Resmi Rekapitulasi Kerusakan Fasilitas</h3>
        <p>Waktu Cetak: {{ date('d/m/Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 15%;">Pelapor</th>
                <th style="width: 20%;">Nama Barang</th>
                <th style="width: 15%;">Lokasi</th>
                <th style="width: 23%;">Kerusakan</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $lap)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $lap->created_at->format('d/m/Y') }}</td>
                <td>{{ $lap->user->nama ?? $lap->user->name ?? '-' }}</td>
                <td>{{ $lap->barang->nama_barang ?? '-' }}</td>
                <td>{{ $lap->barang->lokasi ?? '-' }}</td>
                <td>{{ $lap->deskripsi_kerusakan }}</td>
                <td class="text-center">{{ $lap->status_laporan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #666;">Belum ada data laporan kerusakan fasilitas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Pekanbaru, {{ date('d F Y') }}</p>
        <p>Mengetahui,<br><strong>Pimpinan / Manager</strong></p>
        <br><br><br>
        <p><strong>( ___________________ )</strong></p>
    </div>

</body>
</html>