<?php

namespace App\Http\Controllers;

use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekeningController extends Controller
{
    /**
     * Menampilkan halaman manajemen rekening.
     */
    public function index()
    {
        return view('rekening.rekening');
    }

    /**
     * Mengambil data rekening untuk tabel (API) dengan fitur pencarian dan pagination.
     */
    public function getRekeningData(Request $request)
    {
        $idPengguna = Auth::id();
        $search = $request->query('search');

        // Ambil akumulasi total saldo dari semua rekening pengguna
        $totalSaldo = Rekening::where('id_pengguna', $idPengguna)->sum('saldo');

        $query = Rekening::where('id_pengguna', $idPengguna);

        // Filter berdasarkan nama rekening jika ada input pencarian
        if (!empty($search)) {
            $query->where('nama_rekening', 'LIKE', '%' . $search . '%');
        }

        $rekenings = $query->orderBy('created_at', 'desc')->paginate(5);

        // Tambahkan total saldo keseluruhan ke dalam array respon JSON
        $responseData = $rekenings->toArray();
        $responseData['total_semua_rekening'] = $totalSaldo;

        return response()->json($responseData);
    }

    /**
     * Menyimpan data rekening baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_rekening' => 'required|string|max:100',
            'saldo'         => 'numeric|min:0',
            'icon'          => 'required|string',
            'warna'         => 'required|string',
        ]);

        $rekening = Rekening::create([
            'id_pengguna'   => Auth::id(),
            'nama_rekening' => $request->nama_rekening,
            'saldo'         => $request->saldo,
            'icon'          => $request->icon,
            'warna'         => $request->warna,
        ]);

        return response()->json($rekening);
    }

    /**
     * Mengambil data satu rekening untuk ditampilkan (saat akan diedit).
     */
    public function show(Rekening $rekening)
    {
        return response()->json($rekening);
    }

    /**
     * Memperbarui informasi rekening yang sudah ada.
     */
    public function update(Request $request, Rekening $rekening)
    {
        $request->validate([
            'nama_rekening' => 'required|string|max:100',
            'saldo'         => 'numeric|min:0',
            'icon'          => 'required|string',
            'warna'         => 'required|string',
        ]);

        // Perbarui data hanya pada kolom yang diizinkan (keamanan data)
        $rekening->update($request->only(['nama_rekening', 'saldo', 'icon', 'warna']));

        return response()->json($rekening);
    }

    /**
     * Menghapus rekening dari database.
     */
    public function destroy(Rekening $rekening)
    {
        $rekening->delete();
        return response()->json(['message' => 'Rekening berhasil dihapus.']);
    }

    /**
     * Mengambil seluruh data rekening tanpa pagination (untuk kebutuhan dropdown/pilihan).
     */
    public function allRekening()
    {
        $idPengguna = Auth::id();
        $rekenings = Rekening::where('id_pengguna', $idPengguna)
                    ->orderBy('nama_rekening', 'asc')
                    ->get();

        return response()->json($rekenings);
    }
}
