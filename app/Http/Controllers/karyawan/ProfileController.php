<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Menampilkan halaman profil utama
    public function index()
    {
        $user = Auth::user();
        return view('karyawan.profile', compact('user'));
    }

    // Mengupdate informasi profil (Nama, Email, dll)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar maks 2MB
        ]);

        $dataUpdate = [
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        // Cek apakah ada file foto baru yang di-upload
        if ($request->hasFile('foto')) {
            // Simpan file ke folder storage/app/public/foto-profil
            $path = $request->file('foto')->store('foto-profil', 'public');
            $dataUpdate['foto'] = $path;
        }

        // Update data ke database
        \App\Models\User::where('id_user', $user->id_user)->update($dataUpdate);

        return redirect()->route('karyawan.profile')->with('success', 'Profil berhasil diperbarui!');
    }

    // Menampilkan halaman form khusus ganti password
    public function editPassword()
    {
        return view('karyawan.edit-password');
    }

    // Memproses perubahan password baru
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Cek apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        \App\Models\User::where('id_user', $user->id_user)->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}