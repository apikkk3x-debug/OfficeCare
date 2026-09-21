<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    // Menampilkan daftar pengumuman di panel admin
    public function index()
    {
        $pengumumans = Pengumuman::latest()->paginate(10);
        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    // Menyimpan pengumuman baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string',
            'target_role' => 'required|string',
        ]);

        Pengumuman::create([
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'target_role' => $request->target_role,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil dipublikasikan!');
    }

    // Mengubah status aktif/non-aktif pengumuman
    public function toggle($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->is_active = !$pengumuman->is_active;
        $pengumuman->save();

        return redirect()->back()->with('success', 'Status pengumuman berhasil diperbarui!');
    }

    // Menghapus pengumuman
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus.');
    }
}