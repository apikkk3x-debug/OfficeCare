<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // Jika belum login atau role-nya tidak cocok
        if (!Auth::check() || Auth::user()->role !== $role) {
            $userRole = Auth::user()->role ?? null;

            // Kembalikan ke dashboard sesuai hak akses aslinya
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            } elseif ($userRole === 'karyawan') {
                return redirect()->route('karyawan.dashboard')->with('error', 'Akses ditolak!');
            } elseif ($userRole === 'pimpinan') {
                return redirect()->route('pimpinan.dashboard')->with('error', 'Akses ditolak!');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}