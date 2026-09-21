@extends('layouts.app')

@section('content')
<div class="space-y-4 max-w-5xl mx-auto">
    
    <!-- Header Halaman (Gradient Accent Card) -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-900 border border-slate-700/50 p-5 rounded-2xl shadow-md text-white flex justify-between items-center">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1">
                Panel Admin • Detail Pengaduan
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Kelola Detail Laporan & Diskusi</h2>
            <p class="text-slate-300 text-xs mt-0.5">Tinjau informasi laporan, perbarui status, dan berkomunikasi dengan pelapor.</p>
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold px-3.5 py-2 rounded-xl text-xs border border-white/10 transition shadow-sm backdrop-blur-md">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <!-- Layout 2 Kolom Seimbang -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        
        <!-- ================= KOLOM KIRI: Informasi Laporan, Foto, Deskripsi & Aksi Admin (Lebar 7 Kolom) ================= -->
        <div class="lg:col-span-7 bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 space-y-4">
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
                        <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Pelapor</span>
                        <p class="text-slate-800 text-xs font-bold">{{ $laporan->user->name ?? 'Karyawan' }}</p>
                        <p class="text-[11px] text-slate-400">{{ $laporan->user->email ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Status Saat Ini</span>
                        <div class="mt-1">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold inline-block
                                @if($laporan->status_laporan == 'Menunggu') bg-amber-50 text-amber-700 border border-amber-200
                                @elseif($laporan->status_laporan == 'Diproses') bg-indigo-50 text-indigo-700 border border-indigo-200
                                @elseif($laporan->status_laporan == 'Dibatalkan' || $laporan->status_laporan == 'Ditolak') bg-slate-200 text-slate-600 border border-slate-300 line-through
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
                            <div onclick="bukaModalFoto('{{ asset('storage/' . $laporan->foto_kondisi) }}')" class="inline-block border border-slate-200 rounded-xl overflow-hidden bg-white p-1.5 shadow-sm transition hover:border-indigo-400 cursor-pointer">
                                <img src="{{ asset('storage/' . $laporan->foto_kondisi) }}" 
                                     alt="Foto Kerusakan" 
                                     class="rounded-lg max-h-[180px] w-auto object-contain block"
                                     title="Klik untuk memperbesar foto">
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1 italic">Klik gambar untuk memperbesar</p>
                        @else
                            <div class="border border-slate-200 rounded-xl bg-white p-3">
                                <p class="text-slate-400 text-xs italic">Tidak ada foto.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            <!-- Deskripsi Kerusakan -->
            <div>
                <span class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Deskripsi Pengaduan</span>
                <div class="mt-1.5 p-3.5 bg-white rounded-xl border border-slate-200/80 text-slate-700 text-xs leading-relaxed shadow-sm break-words break-all">
                    {{ $laporan->deskripsi_kerusakan }}
                </div>
            </div>

            <!-- Aksi Cepat Admin (Dipindah kembali ke kiri agar tinggi kolom seimbang) -->
            <div class="bg-indigo-50/70 border border-indigo-200/80 p-4 rounded-xl space-y-3 shadow-sm">
                <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-900 flex items-center gap-1.5">
                    <span>⚡ Aksi Cepat Admin</span>
                </h4>
                
                <form action="{{ route('admin.laporan.updateStatus', $laporan->id_laporan) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Status Laporan</label>
                            <select name="status_laporan" class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none font-medium text-slate-700 shadow-sm cursor-pointer">
                                <option value="Menunggu" {{ $laporan->status_laporan == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Diproses" {{ $laporan->status_laporan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ $laporan->status_laporan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Ditolak" {{ $laporan->status_laporan == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Keterangan / Catatan Log</label>
                            <input type="text" name="keterangan" placeholder="Contoh: Petugas menuju lokasi..." class="w-full text-xs bg-white border border-slate-300 rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none text-slate-700 shadow-sm">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl text-xs transition shadow-md shadow-indigo-600/20 cursor-pointer">
                            Simpan Pembaruan 🚀
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= KOLOM KANAN: Ruang Diskusi / Chat Penuh (Lebar 5 Kolom) ================= -->
        <div class="lg:col-span-5 bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 space-y-4 flex flex-col justify-between h-full">
            <div class="space-y-3 w-full">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Ruang Diskusi & Tanggapan</h3>
                        <p class="text-slate-400 text-[11px]">Komunikasi langsung dengan pelapor.</p>
                    </div>
                    <div class="p-1.5 bg-indigo-600 text-white rounded-xl shadow-sm shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Daftar Pesan dengan Tinggi yang Menyesuaikan Kiri -->
                <div class="space-y-3 max-h-[420px] min-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($laporan->komentars as $komentar)
                        @php
                            $isMe = $komentar->id_user == Auth::id();
                        @endphp
                        
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[85%] p-3 rounded-2xl shadow-sm text-xs space-y-1 
                                {{ $isMe ? 'bg-indigo-600 text-white rounded-br-sm' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-sm' }}">
                                
                                <div class="flex justify-between items-center gap-4 text-[10px] {{ $isMe ? 'text-indigo-100' : 'text-slate-400' }}">
                                    <span class="font-bold">
                                        {{ $isMe ? 'Anda (Admin)' : ($komentar->user->name ?? 'Pengguna') }}
                                        @if(!$isMe && ($komentar->user->role ?? false))
                                            <span class="opacity-80 font-normal">({{ ucfirst($komentar->user->role) }})</span>
                                        @endif
                                    </span>
                                    <span>{{ $komentar->created_at->format('H:i') }}</span>
                                </div>
                                
                                <p class="leading-relaxed break-words break-all {{ $isMe ? 'text-white' : 'text-slate-700' }}">
                                    {{ $komentar->pesan }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white/60 rounded-xl border border-dashed border-slate-300">
                            <p class="text-[11px] text-slate-500 italic">Belum ada diskusi. Mulai percakapan di bawah.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Form Kirim Pesan / Tanggapan -->
            <form action="{{ route('laporan.komentar.store', $laporan->id_laporan) }}" method="POST" class="pt-3 flex gap-2 border-t border-slate-200/60 mt-4">
                @csrf
                <input type="text" name="pesan" placeholder="Tulis tanggapan..." class="flex-1 px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-sm" required>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-2 rounded-xl text-xs font-semibold transition shadow-md shadow-indigo-600/20 shrink-0 cursor-pointer">
                    Kirim 💬
                </button>
            </form>
        </div>

    </div> <!-- TUTUP GRID 2 KOLOM -->

    <!-- ================= BAGIAN BAWAH: TIMELINE / LOG RIWAYAT ================= -->
    <div class="bg-slate-100/80 p-5 rounded-2xl shadow-sm border border-slate-200/90 space-y-3 w-full">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200 pb-2">Log Riwayat & Status Laporan</h3>
        
        <div class="max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
            <div class="relative border-l-2 border-slate-300 ml-3 space-y-4 pt-1 pb-1 overflow-visible">
                @forelse($laporan->logs ?? [] as $log)
                    @php
                        $keteranganLog = $log->keterangan;
                        if (str_ends_with(trim($keteranganLog), 'oleh')) {
                            $keteranganLog .= ' ' . ($laporan->user->name ?? $laporan->user->nama ?? 'Karyawan');
                        }
                    @endphp

                    <div class="relative pl-6 pr-2 py-1 group transition-all duration-300">
                        <!-- Titik/Dot Timeline -->
                        <div class="absolute -left-[9px] top-3 h-4 w-4 rounded-full bg-indigo-600 border-2 border-white shadow-sm z-10 shrink-0"></div>
                        
                        <!-- Card Log -->
                        <div id="log-{{ $log->id_log ?? $log->id }}" 
                            class="bg-white border border-slate-200/80 p-3 rounded-xl shadow-sm transition-all duration-500
                                    target:bg-indigo-100 target:border-indigo-400 target:ring-2 target:ring-indigo-500/50 target:shadow-md target:scale-[1.02]">
                            <p class="text-xs font-semibold text-slate-800 flex items-center gap-1.5">
                                Status: 
                                <span class="bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded font-bold text-[10px]">
                                    {{ $log->status_sekarang }}
                                </span>
                            </p>
                            <p class="text-[11px] text-slate-600 mt-1 leading-relaxed break-words break-all">
                                {{ $log->created_at->format('d M Y, H:i') }} • {{ $keteranganLog }}
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

</div>

<!-- ================= MODAL PREVIEW FOTO ================= -->
<div id="modalPreviewFoto" class="fixed inset-0 bg-slate-900/80 backdrop-blur-xs hidden items-center justify-center z-50 p-4" onclick="tutupModalFoto()">
    <div class="relative w-fit max-w-full bg-white rounded-3xl p-4 shadow-2xl overflow-hidden border border-slate-400" onclick="event.stopPropagation()">
        
        <!-- Tombol Tutup -->
        <div class="flex justify-between items-center pb-3 px-1 border-b border-slate-600 mb-3 gap-6">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pratinjau Foto Kerusakan</span>
            <button type="button" onclick="tutupModalFoto()" class="text-slate-400 hover:text-slate-600 font-bold text-lg px-2 py-1 rounded-full hover:bg-slate-100 transition cursor-pointer">✕</button>
        </div>

        <!-- Area Gambar -->
        <div class="flex items-center justify-center bg-slate-900 rounded-2xl overflow-hidden p-2">
            <img id="gambarModalFull" src="" class="max-h-[75vh] w-auto rounded-xl object-contain shadow-sm">
        </div>
    </div>
</div>

<!-- Script Modal Foto & Deep Linking -->
<script>
function bukaModalFoto(url) {
    const modal = document.getElementById('modalPreviewFoto');
    const gambarFull = document.getElementById('gambarModalFull');
    
    gambarFull.src = url;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function tutupModalFoto() {
    const modal = document.getElementById('modalPreviewFoto');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener("DOMContentLoaded", function() {
    if (window.location.hash) {
        const targetCard = document.querySelector(window.location.hash);
        if (targetCard) {
            setTimeout(() => {
                targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 200);
        }
    }
});
</script>
@endsection