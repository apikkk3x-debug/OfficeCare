<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if ($userRole !== $role) {
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Akses ditolak!');
            }

            if ($userRole === 'karyawan') {
                return redirect()->route('karyawan.dashboard')
                    ->with('error', 'Akses ditolak!');
            }

            if ($userRole === 'pimpinan') {
                return redirect()->route('pimpinan.dashboard')
                    ->with('error', 'Akses ditolak!');
            }

            return redirect()->route('login')
                ->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}