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
    public function getRekeningData(Request $request)
    {
        $idPengguna = Auth::id();
        
        // 1. Tangkap input pencarian dari request JavaScript
        $search = $request->query('search');

        // 2. Hitung total saldo (total saldo biasanya tidak terpengaruh filter pencarian)
        $totalSaldo = Rekening::where('id_pengguna', $idPengguna)->sum('saldo');

        // 3. Bangun Query untuk data rekening
        $query = Rekening::where('id_pengguna', $idPengguna);

        // 4. Tambahkan Filter Pencarian jika variabel $search tidak kosong
        if (!empty($search)) {
            $query->where('nama_rekening', 'LIKE', '%' . $search . '%');
        }

        // 5. Eksekusi pagination (tetap 5 data per halaman)
        $rekenings = $query->orderBy('created_at', 'desc')->paginate(5);

        // 6. Siapkan response JSON
        $responseData = $rekenings->toArray();
        $responseData['total_semua_rekening'] = $totalSaldo;

        return response()->json($responseData);
    }

    // FUNGSI API untuk menyimpan data rekening baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_rekening' => 'required|string|max:100',
            'saldo' => 'numeric|min:0',
            'icon' => 'required|string',
            'warna' => 'required|string',
        ]);

        $rekening = Rekening::create([
            'id_pengguna' => Auth::id(),
            'nama_rekening' => $request->nama_rekening,
            'saldo' => $request->saldo,
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
            'icon' => 'required|string',
            'warna' => 'required|string',
        ]);

        // Gunakan ini agar _token tidak ikut diupdate ke database
        $rekening->update($request->only(['nama_rekening', 'saldo', 'icon', 'warna']));

        return response()->json($rekening);
    }

    // FUNGSI API untuk menghapus data rekening
    public function destroy(Rekening $rekening)
    {
        $rekening->delete();
        return response()->json(['message' => 'Rekening berhasil dihapus.']);
    }

    // Tambahkan fungsi ini di RekeningController.php
    public function allRekening()
    {
        $idPengguna = Auth::id();
        $rekenings = Rekening::where('id_pengguna', $idPengguna)
                    ->orderBy('nama_rekening', 'asc')
                    ->get(); // Menggunakan get() bukan paginate() agar semua data keluar

        return response()->json($rekenings);
    }
}
