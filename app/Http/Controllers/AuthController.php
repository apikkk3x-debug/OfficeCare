<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, true)) {
            $user = Auth::user();

            // Validasi Status Verifikasi Admin
            if ($user->status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Akun Anda masih menunggu verifikasi dari Admin.');
            }

            if ($user->status === 'ditolak') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Mohon maaf, pengajuan akun Anda ditolak oleh Admin.');
            }

            $request->session()->regenerate();

            // Pengalihan halaman otomatis berdasarkan role dengan pesan sukses
            $nama = $user->nama;

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . $nama . '! Anda masuk sebagai Administrator.');
            } elseif ($user->role === 'pimpinan') {
                return redirect()->route('pimpinan.dashboard')->with('success', 'Selamat datang, ' . $nama . '! Anda masuk sebagai Pimpinan.');
            }
            return redirect()->route('karyawan.dashboard')->with('success', 'Selamat datang, ' . $nama . '!');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'divisi'   => 'required|string|max:100',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'divisi'   => $request->divisi,
            'password' => Hash::make($request->password),
            'role'     => 'karyawan',
            'status'   => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu verifikasi dari Admin.');
    }
}