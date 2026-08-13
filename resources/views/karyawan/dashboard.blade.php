@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Dashboard Karyawan</h2>
            <p class="text-slate-500 text-sm mt-1">Selamat datang, {{ Auth::user()->name }}. Silakan laporkan kerusakan fasilitas kantor jika ada.</p>
        </div>
        <span class="bg-blue-100 text-blue-700 font-semibold px-4 py-2 rounded-lg text-sm">
            Karyawan Aktif
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Form Laporan Kerusakan Barang</h3>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-lg text-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('laporan.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Pilih Barang Fasilitas</label>
                    <select name="id_barang" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">-- Pilih Barang yang Rusak --</option>
                        @foreach($barangFasilitas as $barang)
                            <option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }} (Lokasi: {{ $barang->lokasi }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-slate-700 text-sm font-semibold mb-2">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi_kerusakan" rows="3" required placeholder="Jelaskan kerusakannya secara singkat..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Kirim Laporan
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Riwayat Laporan Saya</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 border-b">
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Barang</th>
                            <th class="p-3">Kerusakan</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($laporanku as $lap)
                            <tr>
                                <td class="p-3 font-medium text-slate-800">{{ $lap->barang->nama_barang ?? 'Barang Dihapus' }}</td>
                                <td class="p-3 text-slate-600">{{ $lap->deskripsi_kerusakan }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($lap->status_laporan == 'Menunggu') bg-amber-100 text-amber-700
                                        @elseif($lap->status_laporan == 'Diproses') bg-blue-100 text-blue-700
                                        @else bg-green-100 text-green-700 @endif">
                                        {{ $lap->status_laporan }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-slate-400">Belum ada laporan kerusakan yang dikirim.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection