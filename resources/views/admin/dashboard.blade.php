@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Admin Banner (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-6 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Panel Admin • Executive Dashboard
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Ringkasan Sistem & Sarpras</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Pantau seluruh operasional kantor, inventaris aset, permohonan pengadaan, dan pengaduan real-time.</p>
        </div>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl text-xs font-medium transition-opacity duration-500 flex items-center gap-2.5 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- 6 Kartu Statistik Terpadu (Grid 3 Kolom yang Simetris & Seragam) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        
        <!-- Card 1: Total Laporan -->
        <a href="{{ route('admin.laporan.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-indigo-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Laporan Masuk</span>
                    <div class="p-2.5 bg-slate-50 text-indigo-600 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">{{ $totalLaporan ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Total keseluruhan pengaduan</p>
            </div>
            <span class="text-xs font-semibold text-indigo-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Kelola data laporan &rarr;
            </span>
        </a>

        <!-- Card 2: Status Menunggu -->
        <a href="{{ route('admin.laporan.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-amber-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Laporan Menunggu</span>
                    <div class="p-2.5 bg-amber-50/60 text-amber-600 rounded-xl shadow-sm border border-amber-200/60 group-hover:bg-amber-500 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-amber-600 mt-3">{{ $laporanMenunggu ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Perlu ditindaklanjuti segera</p>
            </div>
            <span class="text-xs font-semibold text-amber-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Tinjau laporan menunggu &rarr;
            </span>
        </a>

        <!-- Card 3: Pengadaan Barang Pending -->
        <a href="{{ route('admin.pengadaan.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-purple-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Pengadaan Barang</span>
                    <div class="p-2.5 bg-purple-50/60 text-purple-600 rounded-xl shadow-sm border border-purple-200/60 group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-purple-600 mt-3">{{ $pengadaanPending ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Usulan baru dari karyawan</p>
            </div>
            <span class="text-xs font-semibold text-purple-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Kelola pengadaan barang &rarr;
            </span>
        </a>

        <!-- Card 4: Total Aset Kantor -->
        <a href="{{ route('admin.barang.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-emerald-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Manajemen Aset</span>
                    <div class="p-2.5 bg-emerald-50/60 text-emerald-600 rounded-xl shadow-sm border border-emerald-200/60 group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-emerald-600 mt-3">{{ $totalAset ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Total unit inventaris terdaftar</p>
            </div>
            <span class="text-xs font-semibold text-emerald-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Kelola inventaris aset &rarr;
            </span>
        </a>

        <!-- Card 5: Pengguna Terdaftar -->
        <a href="{{ route('admin.users') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-blue-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Pengguna Sistem</span>
                    <div class="p-2.5 bg-blue-50/60 text-blue-600 rounded-xl shadow-sm border border-blue-200/60 group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-blue-600 mt-3">{{ $totalUsers ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Total akun terdaftar di sistem</p>
            </div>
            <span class="text-xs font-semibold text-blue-600 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Kelola data pengguna &rarr;
            </span>
        </a>

        <!-- Card 6: Cetak Dokumen -->
        <a href="{{ route('admin.cetakLaporan') }}" target="_blank" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col justify-between transition hover:border-slate-400 hover:shadow-md group">
            <div>
                <div class="flex justify-between items-start">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Arsip & Dokumen</span>
                    <div class="p-2.5 bg-slate-50 text-slate-700 rounded-xl shadow-sm border border-slate-200/60 group-hover:bg-slate-800 group-hover:text-white transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                </div>
                <h4 class="text-3xl font-extrabold text-slate-800 mt-3">{{ $totalLaporan ?? 0 }}</h4>
                <p class="text-slate-500 text-xs mt-1">Total dokumen laporan siap cetak</p>
            </div>
            <span class="text-xs font-semibold text-slate-700 mt-4 flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                Unduh rekap PDF &rarr;
            </span>
        </a>

    </div>

    <!-- Area Grafik Statistik Interaktif -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Grafik 1: Status Laporan Kerusakan -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Status Laporan Kerusakan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Distribusi laporan berdasarkan tahap penanganan</p>
                </div>
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
            </div>
            
            <div class="relative w-full h-52 flex items-center justify-center p-2">
                <canvas id="statusChart"></canvas>
            </div>

            <!-- Kotak Rincian Statistik -->
            @php
                $valMenunggu = $laporanMenunggu ?? 0;
                $valDiproses = $laporanDiproses ?? 0;
                $valSelesai  = $laporanSelesai ?? 0;
                $sumTotal    = $valMenunggu + $valDiproses + $valSelesai;

                $pctMenunggu = $sumTotal > 0 ? round(($valMenunggu / $sumTotal) * 100) : 0;
                $pctDiproses = $sumTotal > 0 ? round(($valDiproses / $sumTotal) * 100) : 0;
                $pctSelesai  = $sumTotal > 0 ? round(($valSelesai / $sumTotal) * 100) : 0;
            @endphp
            <div class="grid grid-cols-3 gap-3 pt-2 border-t border-slate-100 text-center">
                <div class="p-2.5 bg-amber-50/60 rounded-xl border border-amber-100">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-amber-700">Menunggu</span>
                    <span class="text-sm font-extrabold text-amber-800">{{ $valMenunggu }} item</span>
                    <span class="block text-[11px] font-semibold text-amber-600">({{ $pctMenunggu }}%)</span>
                </div>
                <div class="p-2.5 bg-indigo-50/60 rounded-xl border border-indigo-100">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-indigo-700">Diproses</span>
                    <span class="text-sm font-extrabold text-indigo-800">{{ $valDiproses }} item</span>
                    <span class="block text-[11px] font-semibold text-indigo-600">({{ $pctDiproses }}%)</span>
                </div>
                <div class="p-2.5 bg-emerald-50/60 rounded-xl border border-emerald-100">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-emerald-700">Selesai</span>
                    <span class="text-sm font-extrabold text-emerald-800">{{ $valSelesai }} item</span>
                    <span class="block text-[11px] font-semibold text-emerald-600">({{ $pctSelesai }}%)</span>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Tingkat Prioritas Kerusakan -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Tingkat Prioritas Kerusakan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Jumlah aktual per kategori tingkat urgensi</p>
                </div>
                <div class="p-2 bg-rose-50 text-rose-600 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            
            <div class="relative w-full h-52 flex items-center justify-center p-2">
                <canvas id="prioritasChart"></canvas>
            </div>

            <!-- Kotak Rincian Prioritas -->
            @php
                $valRendah  = $prioritasRendah ?? 0;
                $valSedang  = $prioritasSedang ?? 0;
                $valDarurat = $prioritasDarurat ?? 0;
                $sumPrio    = $valRendah + $valSedang + $valDarurat;

                $pctRendah  = $sumPrio > 0 ? round(($valRendah / $sumPrio) * 100) : 0;
                $pctSedang  = $sumPrio > 0 ? round(($valSedang / $sumPrio) * 100) : 0;
                $pctDarurat = $sumPrio > 0 ? round(($valDarurat / $sumPrio) * 100) : 0;
            @endphp
            <div class="grid grid-cols-3 gap-3 pt-2 border-t border-slate-100 text-center">
                <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/80">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500">Rendah</span>
                    <span class="text-sm font-extrabold text-slate-700">{{ $valRendah }} item</span>
                    <span class="block text-[11px] font-semibold text-slate-500">({{ $pctRendah }}%)</span>
                </div>
                <div class="p-2.5 bg-amber-50/60 rounded-xl border border-amber-100">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-amber-700">Sedang</span>
                    <span class="text-sm font-extrabold text-amber-800">{{ $valSedang }} item</span>
                    <span class="block text-[11px] font-semibold text-amber-600">({{ $pctSedang }}%)</span>
                </div>
                <div class="p-2.5 bg-rose-50/60 rounded-xl border border-rose-100">
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-rose-700">Darurat</span>
                    <span class="text-sm font-extrabold text-rose-800">{{ $valDarurat }} item</span>
                    <span class="block text-[11px] font-semibold text-rose-600">({{ $pctDarurat }}%)</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Action Bar -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-bold text-slate-800">Butuh Meninjau Seluruh Pengaduan?</h3>
            <p class="text-slate-500 text-xs mt-0.5">Seluruh data rincian pelapor, foto kerusakan, dan pengubahan status tersedia terpusat di halaman Data Laporan.</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 shrink-0">
            <span>Buka Halaman Data Laporan</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>

</div>

<!-- Panggil Library Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Konfigurasi Global Font Chart.js
    Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
    Chart.defaults.color = '#64748b';

    // 1. Script Grafik Status Laporan
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Diproses', 'Selesai'],
            datasets: [{
                data: [
                    {{ $valMenunggu }}, 
                    {{ $valDiproses }}, 
                    {{ $valSelesai }}
                ],
                backgroundColor: ['#f59e0b', '#6366f1', '#10b981'],
                borderWidth: 4,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { 
                    position: 'bottom', 
                    labels: { 
                        boxWidth: 10,
                        boxHeight: 10,
                        borderRadius: 4,
                        useBorderRadius: true,
                        font: { size: 11, weight: '600' },
                        padding: 15
                    } 
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 11 },
                    padding: 10,
                    cornerRadius: 8
                }
            }
        }
    });

    // 2. Script Grafik Tingkat Prioritas
    const ctxPrioritas = document.getElementById('prioritasChart').getContext('2d');
    new Chart(ctxPrioritas, {
        type: 'bar',
        data: {
            labels: ['Rendah', 'Sedang', 'Darurat'],
            datasets: [{
                label: 'Jumlah Laporan',
                data: [
                    {{ $valRendah }}, 
                    {{ $valSedang }}, 
                    {{ $valDarurat }}
                ],
                backgroundColor: ['#cbd5e1', '#fbbf24', '#f43f5e'],
                borderRadius: { topLeft: 6, topRight: 6 },
                maxBarThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: '600' } }
                },
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1, font: { size: 11 } },
                    grid: { color: '#f1f5f9', borderDash: [4, 4] }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12, weight: 'bold' },
                    bodyFont: { size: 11 },
                    padding: 10,
                    cornerRadius: 8
                }
            }
        }
    });
</script>
@endsection