@extends('layouts.app')

@section('content')
<div class="space-y-4 max-w-5xl mx-auto">
    
    <!-- Header Halaman -->
    <div class="bg-[rgb(255,232,157)] p-4 rounded-xl shadow-sm border border-amber-400/85 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-stone-800">Detail Laporan & Timeline</h2>
            <p class="text-stone-700 text-xs mt-0.5">Informasi status lengkap beserta riwayat aktivitas perubahannya.</p>
        </div>
        <a href="{{ route('laporan.index') }}" class="bg-amber-100 hover:bg-amber-300 text-amber-900 font-semibold px-3 py-1.5 rounded-lg text-xs border border-amber-200 transition shadow-sm">
            &larr; Kembali
        </a>
    </div>

    <!-- Layout 2 Kolom (Kiri: Detail Laporan, Kanan: Timeline dengan Scroll) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        
        <!-- KOLOM KIRI: Detail Laporan (Lebar 7 Kolom) -->
        <div class="lg:col-span-7 bg-[rgb(255,232,157)] p-5 rounded-xl shadow-sm border border-amber-400/85 space-y-4">
            <h3 class="text-sm font-bold text-stone-800 border-b border-amber-300/60 pb-2">Informasi Laporan</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                <!-- Informasi Barang & Laporan -->
                <div class="space-y-3">
                    <div>
                        <span class="text-stone-600 text-[10px] font-semibold uppercase tracking-wider">Nama Barang / Fasilitas</span>
                        <h3 class="text-base font-bold text-stone-900">{{ $laporan->barang->nama_barang ?? 'Barang Dihapus' }}</h3>
                    </div>

                    <div>
                        <span class="text-stone-600 text-[10px] font-semibold uppercase tracking-wider">Lokasi</span>
                        <p class="text-stone-800 text-sm font-medium">{{ $laporan->barang->lokasi ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="text-stone-600 text-[10px] font-semibold uppercase tracking-wider">Status Laporan</span>
                        <div class="mt-0.5">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                @if($laporan->status_laporan == 'Menunggu') bg-amber-100 text-amber-900 border border-amber-300
                                @elseif($laporan->status_laporan == 'Diproses') bg-amber-100 text-amber-900 border border-amber-300
                                @elseif($laporan->status_laporan == 'Dibatalkan') bg-stone-200 text-stone-700 border border-stone-300
                                @else bg-amber-100 text-stone-700 border border-amber-300 @endif">
                                {{ $laporan->status_laporan }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="text-stone-600 text-[10px] font-semibold uppercase tracking-wider">Tanggal Pengajuan</span>
                        <p class="text-stone-800 text-xs font-medium">{{ $laporan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Foto Bukti Kerusakan -->
                <div class="space-y-1.5 flex flex-col items-center sm:items-start">
                    <span class="text-stone-600 text-[10px] font-semibold uppercase tracking-wider">Foto Kerusakan</span>
                    <div>
                        @if($laporan->foto_kondisi)
                            <a href="{{ asset('storage/' . $laporan->foto_kondisi) }}" target="_blank" class="inline-block border border-amber-300/80 rounded-xl overflow-hidden bg-amber-100/40 p-1.5 shadow-sm transition hover:opacity-95">
                                <img src="{{ asset('storage/' . $laporan->foto_kondisi) }}" 
                                     alt="Foto Kerusakan" 
                                     class="rounded-lg max-h-[200px] w-auto object-contain block"
                                     title="Klik untuk melihat foto ukuran penuh">
                            </a>
                        @else
                            <div class="border border-amber-300/80 rounded-xl bg-amber-100/40 p-3">
                                <p class="text-stone-500 text-xs italic">Tidak ada foto.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="border-amber-300/60">

            <!-- Deskripsi Kerusakan -->
            <div>
                <span class="text-stone-600 text-[10px] font-semibold uppercase tracking-wider">Keterangan / Deskripsi Pengaduan</span>
                <div class="mt-1 p-3 bg-amber-50/60 rounded-lg border border-amber-200 text-stone-800 text-xs leading-relaxed">
                    {{ $laporan->deskripsi_kerusakan }}
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Timeline dengan Fitur Scroll (Lebar 5 Kolom) -->
        <div class="lg:col-span-5 bg-[rgb(255,232,157)] p-5 rounded-xl shadow-sm border border-amber-400/85 flex flex-col h-full">
            <h3 class="text-sm font-bold text-stone-800 mb-3 border-b border-amber-300/60 pb-2">Riwayat & Timeline Status</h3>
            
            <!-- Kotak khusus dengan batas tinggi dan scroll (max-h-[350px] overflow-y-auto) -->
            <div class="max-h-[350px] overflow-y-auto pr-2 space-y-4 custom-scrollbar">
                <div class="relative border-l-2 border-amber-300 ml-2 space-y-4 pt-1">
                    @forelse($laporan->logs ?? [] as $log)
                        <div class="relative pl-5">
                            <!-- Titik/Dot Timeline -->
                            <div class="absolute -left-[7px] top-0.5 h-3 w-3 rounded-full bg-amber-500 border-2 border-white shadow-sm"></div>
                            
                            <p class="text-xs font-semibold text-stone-900">
                                Status ke: <span class="bg-amber-100 text-amber-900 px-1.5 py-0.5 rounded font-bold text-[10px]">{{ $log->status_sekarang }}</span>
                            </p>
                            <p class="text-[11px] text-stone-600 mt-0.5">
                                {{ $log->created_at->format('d M Y, H:i') }} • {{ $log->keterangan }}
                            </p>
                        </div>
                    @empty
                        <div class="pl-5 py-4">
                            <p class="text-[11px] text-stone-600 italic">Belum ada catatan riwayat perubahan untuk laporan ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Bagian Diskusi / Komentar -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mt-6 space-y-4">
    <h3 class="text-lg font-bold text-slate-800">Diskusi & Catatan Laporan</h3>
    
    <!-- Kotak Daftar Pesan -->
    <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
        @forelse($laporan->komentars as $komentar)
            <div class="p-3 rounded-xl {{ $komentar->id_user == Auth::id() ? 'bg-indigo-50 ml-6' : 'bg-slate-50 mr-6' }} border border-slate-100">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-bold text-xs text-slate-700">
                        {{ $komentar->user->name ?? 'Pengguna' }} 
                        @if($komentar->user->role ?? false)
                            <span class="text-[10px] bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded ml-1">{{ $komentar->user->role }}</span>
                        @endif
                    </span>
                    <span class="text-[10px] text-slate-400">{{ $komentar->created_at->format('d M Y, H:i') }}</span>
                </div>
                <p class="text-sm text-slate-600">{{ $komentar->pesan }}</p>
            </div>
        @empty
            <p class="text-sm text-slate-400 italic text-center py-4">Belum ada diskusi pada laporan ini. Mulai percakapan di bawah.</p>
        @endforelse
    </div>

    <!-- Form Kirim Pesan -->
    <form action="{{ route('laporan.komentar.store', $laporan->id_laporan) }}" method="POST" class="mt-4 flex gap-2">
        @csrf
        <input type="text" name="pesan" placeholder="Tulis pesan atau pertanyaan ke admin..." class="flex-1 px-4 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none" required>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-semibold transition cursor-pointer shadow-sm">
            Kirim 💬
        </button>
    </form>
</div>
@endsection