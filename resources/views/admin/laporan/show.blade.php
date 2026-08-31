@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header & Tombol Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Laporan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Panel Detail Laporan -->
        <div class="lg:col-span-1 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h2 class="text-lg font-bold text-slate-800 border-b pb-3">Detail Pengaduan</h2>
            
            <div>
                <label class="text-xs text-slate-400 font-medium">Pelapor</label>
                <p class="text-sm font-semibold text-slate-800">{{ $laporan->user->nama ?? $laporan->user->name ?? '-' }}</p>
                <p class="text-xs text-slate-500">{{ $laporan->user->email ?? '-' }}</p>
            </div>

            <div>
                <label class="text-xs text-slate-400 font-medium">Barang & Lokasi</label>
                <p class="text-sm font-semibold text-slate-800">{{ $laporan->barang->nama_barang ?? $laporan->nama_barang ?? '-' }}</p>
                <p class="text-xs text-slate-500">Lokasi: {{ $laporan->barang->lokasi ?? $laporan->lokasi ?? '-' }}</p>
            </div>

            <div>
                <label class="text-xs text-slate-400 font-medium">Deskripsi Kerusakan</label>
                <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 mt-1">
                    {{ $laporan->deskripsi_kerusakan ?? $laporan->deskripsi ?? '-' }}
                </p>
            </div>

            <div>
                <label class="text-xs text-slate-400 font-medium">Status Saat Ini</label>
                <div class="mt-1">
                    <span class="px-3 py-1 text-xs font-semibold rounded-lg inline-block border bg-indigo-50 text-indigo-700 border-indigo-200">
                        {{ $laporan->status_laporan ?? 'Menunggu' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Panel Tanggapan / Komentar -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-800 border-b pb-3 mb-4">Ruang Diskusi & Tanggapan</h2>
                
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                    @if(isset($laporan->komentars) && $laporan->komentars->count() > 0)
                        @foreach($laporan->komentars as $msg)
                            <div class="flex flex-col {{ $msg->id_user == auth()->id() ? 'items-end' : 'items-start' }}">
                                <div class="max-w-md rounded-2xl px-4 py-2 text-sm {{ $msg->id_user == auth()->id() ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-800' }}">
                                    <p class="text-xs opacity-75 mb-1 font-semibold">{{ $msg->user->nama ?? $msg->user->name ?? 'User' }}</p>
                                    <p>{{ $msg->pesan ?? $msg->komentars }}</p>
                                </div>
                                <span class="text-[10px] text-slate-400 mt-1">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-slate-400 text-sm py-8">Belum ada tanggapan pada laporan ini.</p>
                    @endif
                </div>
            </div>

            <!-- Form Tanggapan -->
            <form action="{{ route('admin.laporan.tanggapi', $laporan->id_laporan ?? $laporan->id) }}" method="POST" class="mt-6 pt-4 border-t border-slate-100 flex gap-2">
                @csrf
                <input type="text" name="pesan" required placeholder="Tulis tanggapan atau pembaruan status..." class="flex-1 bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition shadow-md">
                    Kirim
                </button>
            </form>
        </div>
    </div>
</div>
@endsection