<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKomentar;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        $laporan = LaporanKerusakan::where('id_laporan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        LaporanKomentar::create([
            'id_laporan' => $laporan->id_laporan,
            'id_user' => Auth::id(),
            'pesan' => $request->pesan,
        ]);

        return redirect()->back()->with(
            'success',
            'Pesan berhasil dikirim!'
        );
    }
}