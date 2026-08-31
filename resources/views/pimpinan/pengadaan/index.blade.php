@extends('layouts.app')

@section('content')
<div class="space-y-6">
    
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 border border-indigo-700/50 rounded-2xl p-5 shadow-md text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-indigo-500/30 border border-indigo-400/30 text-indigo-200 text-[10px] font-semibold tracking-wider uppercase rounded-md mb-1.5">
                Pengadaan Barang
            </span>
            <h2 class="text-xl font-bold text-white tracking-wide">Daftar Pengajuan Barang Baru</h2>
            <p class="text-xs text-indigo-100/80 mt-1">Pantau status permohonan fasilitas/barang baru yang kamu ajukan ke Pimpinan.</p>
        </div>
        
        <a href="{{ route('karyawan.pengadaan.create') }}" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs transition shadow-md shadow-emerald-600/20 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Ajukan Barang Baru</span>
        </a>
    </div>

    <!-- Tabel Daftar Pengajuan -->
    <div class="bg-slate-100/80 p-5 rounded-2xl border border-slate-200/90 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-200/60 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Estimasi Harga</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Tanggal Pengajuan</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/80 bg-white/70">
                    @forelse($daftarPengadaan as $index => $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-3 font-bold text-slate-800">{{ $item->nama_barang_baru ?? $item->nama_barang }}</td>
                            <td class="p-3 font-medium">{{ $item->jumlah }} Unit</td>
                            <td class="p-3 font-medium text-slate-600">
                                {{ $item->estimasi_harga ? 'Rp ' . number_format($item->estimasi_harga, 0, ',', '.') : '-' }}
                            </td>
                            <td class="p-3">
                                @php
                                    $status = strtolower($item->status_approval ?? $item->status ?? 'pending');
                                    $badge = [
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    ][$status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase border {{ $badge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="p-3 text-slate-400 text-[11px]">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</td>
                            <td class="p-3 text-center">
                                <button type="button" 
                                    onclick="showDetail('{{ $item->nama_barang_baru ?? $item->nama_barang }}', '{{ $item->jumlah }}', '{{ $item->estimasi_harga ? 'Rp ' . number_format($item->estimasi_harga, 0, ',', '.') : '-' }}', '{{ addslashes($item->alasan_pengadaan ?? $item->alasan) }}', '{{ ucfirst($status) }}', '{{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}')"
                                    class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-semibold px-3 py-1.5 rounded-lg text-[11px] border border-indigo-200 transition cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-400">
                                Belum ada riwayat pengajuan barang baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Detail Pengadaan Karyawan -->
<div id="detailModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-800">Rincian Pengadaan Barang</h3>
            <button onclick="toggleDetailModal(false)" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
        </div>

        <div class="space-y-3 text-xs text-slate-600">
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Nama Barang</span>
                <p id="modalNamaBarang" class="font-bold text-slate-800 text-sm mt-0.5"></p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <span class="block text-[10px] font-bold uppercase text-slate-400">Jumlah</span>
                    <p id="modalJumlah" class="font-semibold text-slate-700 mt-0.5"></p>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase text-slate-400">Estimasi Harga</span>
                    <p id="modalHarga" class="font-semibold text-slate-700 mt-0.5"></p>
                </div>
            </div>
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Status Permohonan</span>
                <p id="modalStatus" class="font-semibold mt-0.5"></p>
            </div>
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Tanggal Pengajuan</span>
                <p id="modalTanggal" class="font-medium text-slate-600 mt-0.5"></p>
            </div>
            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Alasan / Kebutuhan</span>
                <p id="modalAlasan" class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 leading-relaxed mt-1"></p>
            </div>
        </div>

        <div class="flex justify-end pt-3 border-t border-slate-100">
            <button type="button" onclick="toggleDetailModal(false)" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-700 transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function toggleDetailModal(show) {
        const modal = document.getElementById('detailModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function showDetail(nama, jumlah, harga, alasan, status, tanggal) {
        document.getElementById('modalNamaBarang').innerText = nama;
        document.getElementById('modalJumlah').innerText = jumlah + ' Unit';
        document.getElementById('modalHarga').innerText = harga;
        document.getElementById('modalStatus').innerText = status;
        document.getElementById('modalTanggal').innerText = tanggal;
        document.getElementById('modalAlasan').innerText = alasan;
        toggleDetailModal(true);
    }
</script>
@endsection