<?php

namespace App\Http\Controllers;

use App\Models\PengadaanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PengadaanController extends Controller
{
    // Halaman form pengajuan untuk Karyawan
    public function create()
    {
        return view('karyawan.pengadaan.create');
    }

    // Proses menyimpan pengajuan dari Karyawan
   public function store(Request $request)
{
    // Validasi data input dari karyawan
    $request->validate([
        'nama_barang_baru' => 'required|string|max:255',
        'jumlah'           => 'required|integer|min:1',
        'estimasi_harga'   => 'required|numeric|min:0', // Sekarang wajib & harus angka positif
        'link_referensi'   => 'nullable|url|max:500',   // Opsional tapi harus berformat URL jika diisi
        'alasan_pengadaan' => 'required|string',
    ]);

    // Simpan ke database
    \App\Models\PengadaanBarang::create([
        'id_user'          => Auth::id(), // Sesuaikan dengan foreign key user Anda
        'nama_barang_baru' => $request->nama_barang_baru,
        'jumlah'           => $request->jumlah,
        'estimasi_harga'   => $request->estimasi_harga,
        'link_referensi'   => $request->link_referensi,
        'alasan_pengadaan' => $request->alasan_pengadaan,
        'status_approval'  => 'Pending', // Atau status default pengadaan baru
    ]);

    return redirect()->route('karyawan.pengadaan.index')->with('success', 'Pengajuan pengadaan barang berhasil dikirim ke Pimpinan.');
}
    public function index(Request $request)
{
    $perPage = $request->input('per_page', 10);

    $pengadaanku = \App\Models\PengadaanBarang::where('id_user', Auth::id())
                        ->latest()
                        ->paginate($perPage)
                        ->withQueryString();

    return view('karyawan.pengadaan.index', compact('pengadaanku'));
}

public function show($id)
{
    $pengadaan = \App\Models\PengadaanBarang::where('id_pengadaan', $id)
                    ->where('id_user', Auth::id())
                    ->firstOrFail();

    return view('karyawan.pengadaan.show', compact('pengadaan'));
}

public function edit($id)
{
    $pengadaan = \App\Models\PengadaanBarang::where('id_pengadaan', $id)
                    ->where('id_user', Auth::id())
                    ->firstOrFail();

    $rawStatus = strtolower($pengadaan->status_approval ?? $pengadaan->status ?? 'pending');
    if (!in_array($rawStatus, ['pending', 'menunggu'])) {
        return redirect()->route('karyawan.pengadaan.index')->with('error', 'Pengajuan yang sudah diproses tidak dapat diubah.');
    }

    return view('karyawan.pengadaan.edit', compact('pengadaan'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_barang_baru' => 'required|string|max:255',
        'jumlah'           => 'required|integer|min:1',
        'estimasi_harga'   => 'required|numeric|min:0',
        'link_referensi'   => 'nullable|url|max:500',
        'alasan_pengadaan' => 'required|string',
    ]);

    $pengadaan = \App\Models\PengadaanBarang::where('id_pengadaan', $id)
                    ->where('id_user', Auth::id())
                    ->firstOrFail();

    $pengadaan->update([
        'nama_barang_baru' => $request->nama_barang_baru,
        'jumlah'           => $request->jumlah,
        'estimasi_harga'   => $request->estimasi_harga,
        'link_referensi'   => $request->link_referensi,
        'alasan_pengadaan' => $request->alasan_pengadaan,
    ]);

    return redirect()->route('karyawan.pengadaan.index')
                     ->with('success', 'Pengajuan pengadaan berhasil diperbarui.');
}

public function destroy($id)
{
    $pengadaan = \App\Models\PengadaanBarang::where('id_pengadaan', $id)
                    ->where('id_user', Auth::id())
                    ->firstOrFail();

    $rawStatus = strtolower($pengadaan->status_approval ?? $pengadaan->status ?? 'pending');
    if (!in_array($rawStatus, ['pending', 'menunggu'])) {
        return redirect()->route('karyawan.pengadaan.index')->with('error', 'Pengajuan yang sudah diproses tidak dapat dibatalkan.');
    }

    $pengadaan->delete();

    return redirect()->route('karyawan.pengadaan.index')
                     ->with('success', 'Pengajuan pengadaan berhasil dibatalkan.');
}
}