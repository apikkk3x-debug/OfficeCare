<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ProfileController extends Controller
{
    // Menampilkan halaman profil utama
    public function index()
    {
        $user = Auth::user();
        return view('karyawan.profile', compact('user'));
    }

    // Mengupdate informasi profil (Nama, Email, Foto)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id_user . ',id_user',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $dataUpdate = [
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto-profil', 'public');
            $dataUpdate['foto'] = $path;
        }

        User::where('id_user', $user->id_user)->update($dataUpdate);

        // Diubah menggunakan back() agar tetap berada di halaman profil asal dengan membawa pesan sukses
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // Menampilkan halaman form khusus ganti password (opsional)
    public function editPassword()
    {
        return view('karyawan.edit-password');
    }

    // 1. Memproses perubahan password biasa via AJAX (Mode Langsung)
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.different' => 'Kata sandi baru tidak boleh sama dengan kata sandi lama!',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();

        // Cek apakah kata sandi lama sesuai dengan database
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi lama yang Anda masukkan salah!'
            ], 422);
        }

        User::where('id_user', $user->id_user)->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui!'
        ]);
    }

    // 2. Mengirim Kode OTP ke Email Gmail User (AJAX)
    public function sendOtp(Request $request)
    {
        $user = Auth::user();
        $otp = rand(100000, 999999);
        
        Session::put('password_otp', $otp);
        Session::put('password_otp_expires', now()->addMinutes(5));

        try {
            Mail::raw("Halo {$user->nama},\n\nKode OTP verifikasi ubah kata sandi akun OfficeCare Anda adalah: {$otp}\n\nKode ini berlaku selama 5 menit.", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Kode OTP Verifikasi Ubah Sandi - OfficeCare');
            });

            return response()->json([
                'success' => true, 
                'message' => 'Kode OTP berhasil dikirim ke alamat Gmail Anda!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengirim email OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    // 3. Verifikasi OTP & Update Password Baru via Modal (AJAX)
    public function verifyAndUpdatePassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Cek agar kata sandi baru tidak sama dengan kata sandi akun saat ini
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false, 
                'message' => 'Kata sandi baru tidak boleh sama dengan kata sandi Anda saat ini!'
            ], 422);
        }

        $sessionOtp = Session::get('password_otp');
        $expiresAt = Session::get('password_otp_expires');

        if (!$sessionOtp || now()->greaterThan($expiresAt)) {
            return response()->json([
                'success' => false, 
                'message' => 'Kode OTP sudah kadaluarsa atau tidak valid. Silakan minta kode baru.'
            ], 422);
        }

        if ($request->otp != $sessionOtp) {
            return response()->json([
                'success' => false, 
                'message' => 'Kode OTP yang Anda masukkan salah!'
            ], 422);
        }

        User::where('id_user', $user->id_user)->update([
            'password' => Hash::make($request->password),
        ]);

        Session::forget(['password_otp', 'password_otp_expires']);

        return response()->json([
            'success' => true, 
            'message' => 'Kata sandi Anda berhasil diperbarui!'
        ]);
    }
}