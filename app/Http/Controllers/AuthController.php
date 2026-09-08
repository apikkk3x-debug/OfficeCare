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

    // Menambahkan method showRegister yang sebelumnya belum ada
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

            // Pengalihan halaman otomatis berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'pimpinan') {
                return redirect()->route('pimpinan.dashboard');
            }
            return redirect()->route('karyawan.dashboard');
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'nik'      => 'required|string|max:50|unique:users',
            'divisi'   => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'nama'     => $request->name,
            'email'    => $request->email,
            'nik'      => $request->nik,
            'divisi'   => $request->divisi,
            'password' => Hash::make($request->password),
            'role'     => 'karyawan',
            'status'   => 'pending',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda sedang menunggu verifikasi dari Admin.');
    }
}