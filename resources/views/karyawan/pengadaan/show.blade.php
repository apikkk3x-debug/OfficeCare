@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Detail Pengadaan
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Rincian Pengajuan Barang</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Informasi lengkap permohonan fasilitas atau barang operasional kantor.</p>
        </div>
    </div>

    <!-- Detail Content Card -->
    <div class="bg-slate-100/80 p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Barang -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Nama Barang / Fasilitas</span>
                <p class="text-sm font-bold text-slate-800">{{ $pengadaan->nama_barang_baru ?? $pengadaan->nama_barang }}</p>
            </div>

            <!-- Status Pengajuan -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Status Saat Ini</span>
                @php
                    $rawStatus = strtolower($pengadaan->status_approval ?? $pengadaan->status ?? 'pending');
                    $statusConfig = [
                        'pending'   => ['bg' => 'bg-amber-100 text-amber-700 border-amber-200',    'label' => 'Menunggu Pimpinan'],
                        'disetujui' => ['bg' => 'bg-blue-100 text-blue-700 border-blue-200',      'label' => 'Proses Pembelian (Admin)'],
                        'selesai'   => ['bg' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'label' => 'Barang Diterima / Selesai'],
                        'ditolak'   => ['bg' => 'bg-rose-100 text-rose-700 border-rose-200',      'label' => 'Pengajuan Ditolak'],
                    ];
                    $config = $statusConfig[$rawStatus] ?? $statusConfig['pending'];
                @endphp
                <span class="px-3 py-1 rounded-lg text-xs font-bold border inline-block {{ $config['bg'] }}">
                    {{ $config['label'] }}
                </span>
            </div>

            <!-- Jumlah Unit -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Jumlah Unit</span>
                <p class="text-sm font-semibold text-slate-700">{{ $pengadaan->jumlah }} Unit</p>
            </div>

            <!-- Estimasi Harga -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Estimasi Harga Per Unit</span>
                <p class="text-sm font-semibold text-slate-700">Rp {{ number_format($pengadaan->estimasi_harga ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Tautan / Link Referensi Produk -->
        <div class="pt-4 border-t border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Tautan Referensi Produk (Marketplace / Katalog):</span>
            @if(!empty($pengadaan->link_referensi))
                <a href="{{ $pengadaan->link_referensi }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200/80 rounded-xl text-xs font-semibold transition shadow-sm">
                    <span>Buka Tautan Eksternal</span>
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            @else
                <p class="text-xs text-slate-400 italic">Tidak ada tautan referensi yang dilampirkan pada pengajuan ini.</p>
            @endif
        </div>

        <!-- Alasan Pengadaan -->
        <div class="pt-4 border-t border-slate-200">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">Alasan / Kebutuhan Pengadaan</span>
            <p class="text-xs text-slate-700 leading-relaxed bg-white p-4 rounded-xl border border-slate-200">
                {{ $pengadaan->alasan_pengadaan ?? $pengadaan->alasan }}
            </p>
        </div>

        <!-- Tombol Kembali -->
        <div class="flex justify-end pt-4 border-t border-slate-200">
            <a href="{{ route('karyawan.pengadaan.index') }}" 
               class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                Kembali ke Daftar
            </a>
        </div>

    </div>

</div>
@endsection