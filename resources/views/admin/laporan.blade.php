@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header Admin Banner -->
    <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-lg flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border border-slate-800">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold bg-indigo-600 px-3 py-1 rounded-full uppercase tracking-wider text-indigo-100">Manajemen Admin</span>
                <span class="text-xs text-slate-400">Sarpras System</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Data Semua Laporan Masuk</h1>
            <p class="text-slate-300 text-sm mt-1">Pantau, ubah status, tanggapi pesan karyawan, dan kelola pengaduan kerusakan sarana prasarana.</p>
        </div>
        
        <a href="{{ route('admin.cetakLaporan') }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition shadow-md flex items-center gap-2 border border-indigo-400/30 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Cetak Laporan</span>
        </a>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div id="success-alert" class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-sm font-medium transition-opacity duration-500 flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tabel Data Laporan Lengkap -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-bold text-slate-800">Daftar Pengaduan Kerusakan</h2>
                <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-0.5 rounded-full border border-indigo-200">
                    Total: {{ $laporan->count() }}
                </span>
            </div>
        </div>
        
        <!-- Wrapper Scroll Vertikal (Maksimal 5 Baris ~320px, Tanpa Scroll Samping) -->
        <div class="max-h-[320px] overflow-y-auto overflow-x-hidden">
            <table class="w-full text-xs text-left table-fixed">
                <thead class="sticky top-0 bg-slate-100 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200 z-10">
                    <tr>
                        <th class="p-3.5 w-28">Tanggal</th>
                        <th class="p-3.5 w-36">Pelapor</th>
                        <th class="p-3.5 w-40">Barang & Lokasi</th>
                        <th class="p-3.5">Deskripsi Kerusakan</th>
                        <th class="p-3.5 w-28 text-center">Status</th>
                        <th class="p-3.5 w-36 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($laporan as $item)
                        @php
                            $tanggal = $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-';
                            $pelapor = $item->user->nama ?? $item->user->name ?? 'Karyawan';
                            $email = $item->user->email ?? '-';
                            $barang = $item->barang->nama_barang ?? $item->nama_barang ?? '-';
                            $lokasi = $item->barang->lokasi ?? $item->lokasi ?? '-';
                            $deskripsi = $item->deskripsi_kerusakan ?? $item->deskripsi ?? $item->keterangan ?? '-';
                            $status = $item->status_laporan ?? 'Menunggu';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            
                            <!-- Tanggal -->
                            <td class="p-3.5 font-medium text-slate-500 truncate" title="{{ $tanggal }}">
                                {{ $tanggal }}
                            </td>

                            <!-- Pelapor -->
                            <td class="p-3.5">
                                <div class="font-bold text-slate-800 truncate" title="{{ $pelapor }}">
                                    {{ $pelapor }}
                                </div>
                                <div class="text-[10px] text-slate-400 truncate" title="{{ $email }}">
                                    {{ $email }}
                                </div>
                            </td>

                            <!-- Barang & Lokasi -->
                            <td class="p-3.5">
                                <div class="font-semibold text-slate-800 truncate" title="{{ $barang }}">
                                    {{ $barang }}
                                </div>
                                <div class="text-[10px] text-slate-500 truncate" title="Lokasi: {{ $lokasi }}">
                                    Lokasi: {{ $lokasi }}
                                </div>
                            </td>

                            <!-- Deskripsi Kerusakan (Truncate 1 baris titik-titik) -->
                            <td class="p-3.5">
                                <p class="text-slate-600 truncate" title="{{ $deskripsi }}">
                                    {{ $deskripsi }}
                                </p>
                            </td>

                            <!-- Badge Status -->
                            <td class="p-3.5 text-center">
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-lg border inline-block
                                    @if($status == 'Selesai') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @elseif($status == 'Diproses') bg-indigo-50 text-indigo-700 border-indigo-200
                                    @elseif($status == 'Dibatalkan' || $status == 'Ditolak') bg-slate-100 text-slate-500 border-slate-200 line-through
                                    @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                                    {{ $status }}
                                </span>
                            </td>

                            <!-- Form Aksi Ubah Status & Detail -->
                            <td class="p-3.5">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Select Status -->
                                    <form action="{{ route('admin.laporan.updateStatus', $item->id_laporan ?? $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status_laporan" onchange="this.form.submit()" class="text-[11px] bg-white border border-slate-300 rounded-lg p-1 focus:ring-2 focus:ring-indigo-500 focus:outline-none cursor-pointer font-medium text-slate-700 shadow-xs">
                                            <option value="Menunggu" {{ $status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="Diproses" {{ $status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="Selesai" {{ $status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="Ditolak" {{ $status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                        </select>
                                    </form>

                                    <!-- Tombol Detail Modal -->
                                    <button type="button" 
                                        onclick="openDetailModal('{{ addslashes($tanggal) }}', '{{ addslashes($pelapor) }}', '{{ addslashes($email) }}', '{{ addslashes($barang) }}', '{{ addslashes($lokasi) }}', '{{ addslashes($deskripsi) }}', '{{ addslashes($status) }}')"
                                        title="Lihat Detail Lengkap"
                                        class="p-1 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-lg border border-slate-300 transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>

                                    <!-- Tombol Obrolan Chat -->
                                    <a href="{{ route('admin.laporan.show', $item->id_laporan ?? $item->id) }}" 
                                        title="Diskusi / Chat"
                                        class="p-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg border border-indigo-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-8 text-slate-400">
                                Belum ada data laporan pengaduan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Pop-up Detail Laporan Rinci -->
<div id="laporanModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
            <h3 class="text-base font-bold text-slate-800">Detail Rincian Laporan</h3>
            <button onclick="toggleModalLaporan(false)" class="text-slate-400 hover:text-slate-600 font-bold text-xl cursor-pointer">&times;</button>
        </div>

        <div class="space-y-3 text-xs text-slate-600">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <span class="block text-[10px] font-bold uppercase text-slate-400">Tanggal Laporan</span>
                    <p id="modalTanggal" class="font-medium text-slate-700 mt-0.5"></p>
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase text-slate-400">Status Saat Ini</span>
                    <p id="modalStatus" class="font-bold mt-0.5"></p>
                </div>
            </div>

            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Pelapor</span>
                <p id="modalPelapor" class="font-bold text-slate-800 text-sm mt-0.5"></p>
                <p id="modalEmail" class="text-[11px] text-slate-400"></p>
            </div>

            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Barang & Lokasi</span>
                <p id="modalBarang" class="font-bold text-indigo-900 text-sm mt-0.5"></p>
                <p id="modalLokasi" class="text-slate-500"></p>
            </div>

            <div>
                <span class="block text-[10px] font-bold uppercase text-slate-400">Deskripsi Kerusakan Lengkap</span>
                <p id="modalDeskripsi" class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 leading-relaxed mt-1 break-words max-h-36 overflow-y-auto"></p>
            </div>
        </div>

        <div class="flex justify-end pt-3 border-t border-slate-100">
            <button type="button" onclick="toggleModalLaporan(false)" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-700 transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function toggleModalLaporan(show) {
        const modal = document.getElementById('laporanModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function openDetailModal(tanggal, pelapor, email, barang, lokasi, deskripsi, status) {
        document.getElementById('modalTanggal').innerText = tanggal;
        document.getElementById('modalStatus').innerText = status;
        document.getElementById('modalPelapor').innerText = pelapor;
        document.getElementById('modalEmail').innerText = email;
        document.getElementById('modalBarang').innerText = barang;
        document.getElementById('modalLokasi').innerText = 'Lokasi: ' + lokasi;
        document.getElementById('modalDeskripsi').innerText = deskripsi;
        toggleModalLaporan(true);
    }

    // Auto-hide alert sukses
    document.addEventListener("DOMContentLoaded", function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }
    });
</script>
@endsection