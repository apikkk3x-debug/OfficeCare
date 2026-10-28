<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Kerusakan Fasilitas</title>
    <style>
        * {
            box-sizing: border-box !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4 portrait;
            margin: 15mm 12mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            margin: 0;
            padding: 0;
            line-height: 1.4;
            background-color: #f1f5f9;
        }

        /* Mode Layar Browser (Tampilan Pratinjau Kertas A4) */
        @media screen {
            body {
                padding: 30px 15px;
            }
            .page-container {
                max-width: 210mm; /* Lebar standar kertas A4 */
                min-height: 297mm;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 15mm;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
                border-radius: 6px;
            }
        }

        /* Mode Cetak Printer (Ctrl + P) */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .page-container {
                max-width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2.5px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 4px 0;
            font-size: 13pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h3 {
            margin: 0 0 6px 0;
            font-size: 11pt;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 8.5pt;
            color: #333;
        }

        /* FRAME TABEL UTAMA */
        table {
            width: 100%;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 2px solid #000000 !important;
        }

        th, td {
            border: 1px solid #000000 !important;
            padding: 7px 8px;
            font-size: 9pt;
            vertical-align: top;
            color: #000;
        }

        th:first-child, td:first-child {
            border-left: 2px solid #000000 !important;
        }
        th:last-child, td:last-child {
            border-right: 2px solid #000000 !important;
        }
        tbody tr:last-child td {
            border-bottom: 2px solid #000000 !important;
        }

        th {
            background-color: #e2e8f0 !important;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
        }

        thead {
            display: table-header-group;
        }

        tbody tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .text-center { text-align: center; }

        .footer {
            float: right;
            text-align: center;
            width: 220px;
            page-break-inside: avoid;
            margin-top: 20px;
        }
        .footer p {
            margin: 3px 0;
            font-size: 9.5pt;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="page-container">

        <!-- Tombol Kembali -->
        <div class="no-print" style="margin-bottom: 20px;">
            <a href="{{ route('pimpinan.rekap') }}" style="display: inline-block; padding: 7px 16px; background-color: #0f172a; color: #fff; text-decoration: none; border-radius: 8px; font-size: 9.5pt; font-weight: bold;">
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
                    <td style="font-weight: bold;">{{ $lap->user->nama ?? $lap->user->name ?? '-' }}</td>
                    <td>{{ $lap->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $lap->barang->lokasi ?? '-' }}</td>
                    <td>{{ $lap->deskripsi_kerusakan }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $lap->status_laporan }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #666; font-style: italic;">
                        Belum ada data laporan kerusakan fasilitas.
                    </td>
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

    </div>

</body>
</html>