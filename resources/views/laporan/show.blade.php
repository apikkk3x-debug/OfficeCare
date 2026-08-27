@extends('layouts.app')

@section('content')
<div class="space-y-4 max-w-5xl mx-auto">
    
    <!-- Header Halaman (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 border border-indigo-700/50 p-5 rounded-2xl shadow-md text-white flex justify-between items-center">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1">
                Detail Laporan
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Detail Laporan & Timeline</h2>
            <p class="text-indigo-100/80 text-xs mt-0.5">Informasi status lengkap beserta riwayat aktivitas perubahannya.</p>
        </div>
        <a href="{{ route('laporan.index') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold px-3.5 py-2 rounded-xl text-xs border border-white/10 transition shadow-sm backdrop-blur-md">
            &larr; Kembali
        </a>
    </div>

    <!-- Layout 2 Kolom (Kiri: Detail Laporan, Kanan: Timeline) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        
        <!-- KOLOM KIRI: Detail Laporan (Lebar 7 Kolom) -->
        <div class="lg:col-span-7 bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 space-y-4 flex flex-col justify-between">
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200 pb-2">Informasi Laporan</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <!-- Informasi Barang & Laporan -->
                    <div class="space-y-3">
                        <div>
                            <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Nama Barang / Fasilitas</span>
                            <h3 class="text-base font-bold text-slate-800 break-words">{{ $laporan->barang->nama_barang ?? 'Barang Dihapus' }}</h3>
                        </div>

                        <div>
                            <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Lokasi</span>
                            <p class="text-slate-700 text-xs font-medium break-words">{{ $laporan->barang->lokasi ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Status Laporan</span>
                            <div class="mt-1">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-block
                                    @if($laporan->status_laporan == 'Menunggu') bg-amber-50 text-amber-700 border border-amber-200
                                    @elseif($laporan->status_laporan == 'Diproses') bg-indigo-50 text-indigo-700 border border-indigo-200
                                    @elseif($laporan->status_laporan == 'Dibatalkan') bg-slate-200 text-slate-600 border border-slate-300 line-through
                                    @else bg-emerald-50 text-emerald-700 border border-emerald-200 @endif">
                                    {{ $laporan->status_laporan }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Tanggal Pengajuan</span>
                            <p class="text-slate-700 text-xs font-medium">{{ $laporan->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Foto Bukti Kerusakan -->
                    <div class="space-y-1.5 flex flex-col items-start">
                        <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Foto Kerusakan</span>
                        <div>
                            @if($laporan->foto_kondisi)
                                <a href="{{ asset('storage/' . $laporan->foto_kondisi) }}" target="_blank" class="inline-block border border-slate-200 rounded-xl overflow-hidden bg-white p-1.5 shadow-sm transition hover:border-indigo-300">
                                    <img src="{{ asset('storage/' . $laporan->foto_kondisi) }}" 
                                         alt="Foto Kerusakan" 
                                         class="rounded-lg max-h-[180px] w-auto object-contain block"
                                         title="Klik untuk melihat foto ukuran penuh">
                                </a>
                            @else
                                <div class="border border-slate-200 rounded-xl bg-white p-3">
                                    <p class="text-slate-400 text-xs italic">Tidak ada foto.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="border-slate-200">

                <!-- Deskripsi Kerusakan (Diberi break-words & break-all) -->
                <div>
                    <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Deskripsi Pengaduan</span>
                    <div class="mt-1.5 p-3.5 bg-white rounded-xl border border-slate-200/80 text-slate-700 text-xs leading-relaxed shadow-sm break-words break-all">
                        {{ $laporan->deskripsi_kerusakan }}
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Timeline (Lebar 5 Kolom) -->
        <div class="lg:col-span-5 bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 flex flex-col h-full">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3 border-b border-slate-200 pb-2">Riwayat & Timeline Status</h3>
            
            <div class="max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                <div class="relative border-l-2 border-slate-300 ml-3 space-y-4 pt-1 pb-1 overflow-visible">
                    @forelse($laporan->logs ?? [] as $log)
                        <!-- PROPERTI target: Highlight warna Indigo -->
                        <div id="log-{{ $log->id_log ?? $log->id }}" 
                             class="relative pl-6 pr-2 py-1 group transition-all duration-300">
                            
                            <!-- Titik/Dot Timeline -->
                            <div class="absolute -left-[9px] top-3 h-4 w-4 rounded-full bg-indigo-600 border-2 border-white shadow-sm z-10 shrink-0"></div>
                            
                            <!-- Kotak Card Log -->
                            <div class="bg-white border border-slate-200/80 p-3 rounded-xl shadow-sm transition-all duration-500
                                        target:bg-indigo-100 target:border-indigo-400 target:ring-2 target:ring-indigo-500/50 target:shadow-md target:scale-[1.02]">
                                <p class="text-xs font-semibold text-slate-800 flex items-center gap-1.5">
                                    Status: 
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded font-bold text-[10px]">
                                        {{ $log->status_sekarang }}
                                    </span>
                                </p>
                                <!-- Keterangan Log (Diberi break-words & break-all) -->
                                <p class="text-[11px] text-slate-600 mt-1 leading-relaxed break-words break-all">
                                    {{ $log->created_at->format('d M Y, H:i') }} • {{ $log->keterangan }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="pl-6 py-4">
                            <p class="text-[11px] text-slate-500 italic">Belum ada catatan riwayat perubahan untuk laporan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div> <!-- TUTUP GRID 2 KOLOM -->

    <!-- Bagian Diskusi / Komentar -->
    <div class="bg-slate-100/80 p-6 rounded-2xl shadow-sm border border-slate-200/90 space-y-4 w-full">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Diskusi & Catatan Laporan</h3>
                <p class="text-slate-500 text-xs">Pesan atau instruksi tambahan terkait penanganan laporan ini.</p>
            </div>
            <div class="p-2 bg-indigo-600 text-white rounded-xl shadow-sm shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
        </div>
        
        <!-- Kotak Daftar Pesan -->
        <div class="space-y-3 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
            @forelse($laporan->komentars as $komentar)
                <div class="p-3.5 rounded-xl {{ $komentar->id_user == Auth::id() ? 'bg-indigo-50/90 border-indigo-200/80 ml-6' : 'bg-white border-slate-200/80' }} border shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-bold text-xs text-slate-800">
                            {{ $komentar->user->name ?? 'Pengguna' }} 
                            @if($komentar->user->role ?? false)
                                <span class="text-[10px] bg-slate-200/80 text-slate-600 px-1.5 py-0.5 rounded ml-1 font-semibold">{{ $komentar->user->role }}</span>
                            @endif
                        </span>
                        <span class="text-[10px] text-slate-400">{{ $komentar->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <!-- Pesan Komentar (Diberi break-words & break-all) -->
                    <p class="text-xs text-slate-600 leading-relaxed break-words break-all">{{ $komentar->pesan }}</p>
                </div>
            @empty
                <div class="text-center py-6 bg-white/60 rounded-xl border border-dashed border-slate-300">
                    <p class="text-xs text-slate-500 italic">Belum ada diskusi pada laporan ini. Mulai percakapan di bawah.</p>
                </div>
            @endforelse
        </div>

        <!-- Form Kirim Pesan -->
        <form action="{{ route('laporan.komentar.store', $laporan->id_laporan) }}" method="POST" class="pt-2 flex gap-2">
            @csrf
            <input type="text" name="pesan" placeholder="Tulis pesan atau pertanyaan ke admin..." class="flex-1 px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm" required>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs font-semibold transition shadow-md shadow-indigo-600/20 shrink-0">
                Kirim 💬
            </button>
        </form>
    </div>

</div>
@endsection