<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekeningController extends Controller
{
    public function index()
    {
        return view('rekening.rekening');
    }

    // FUNGSI API untuk mengambil semua data rekening
    public function getRekeningData()
    {
        $idPengguna = Auth::id();
        $rekenings = Rekening::where('id_pengguna', $idPengguna)->get();
        return response()->json($rekenings);
    }

    // FUNGSI API untuk menyimpan data rekening baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_rekening' => 'required|string|max:100',
            'saldo' => 'numeric|min:0',
            'mata_uang' => 'required|string',
            'icon' => 'required|string',
            'warna' => 'required|string',
        ]);

        $rekening = Rekening::create([
            'id_pengguna' => Auth::id(),
            'nama_rekening' => $request->nama_rekening,
            'saldo' => $request->saldo,
            'mata_uang' => $request->mata_uang,
            'icon' => $request->icon,
            'warna' => $request->warna,
        ]);

        return response()->json($rekening);
    }

    // FUNGSI API untuk mengambil data rekening saat mengklik "Edit"
    public function show(Rekening $rekening)
    {
        return response()->json($rekening);
    }

    // FUNGSI API untuk menyimpan data yang sudah diedit
    public function update(Request $request, Rekening $rekening)
    {
        $request->validate([
            'nama_rekening' => 'required|string|max:100',
            'saldo' => 'numeric|min:0',
            'mata_uang' => 'required|string',
            'icon' => 'required|string',
            'warna' => 'required|string',
        ]);

        // Gunakan ini agar _token tidak ikut diupdate ke database
        $rekening->update($request->only(['nama_rekening', 'saldo', 'mata_uang', 'icon', 'warna']));

        return response()->json($rekening);
    }

    // FUNGSI API untuk menghapus data rekening
    public function destroy(Rekening $rekening)
    {
        $rekening->delete();
        return response()->json(['message' => 'Rekening berhasil dihapus.']);
    }
}
