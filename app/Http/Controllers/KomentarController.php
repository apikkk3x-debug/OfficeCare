<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKomentar;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function store(Request $request, $id)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        LaporanKomentar::create([
            'id_laporan' => $id,
            'id_user' => Auth::id(),
            'pesan' => $request->pesan,
        ]);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}