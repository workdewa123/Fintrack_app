<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    // Halaman utama - Sekarang tanpa mengirim variabel $categories
    public function index()
    {
        return view('kategori.kategori');
    }

    // Fungsi API untuk mengambil data JSON
    public function getKategoriData(Request $request)
    {
        $idPengguna = auth()->id();
        $tipe = $request->query('tipe');

        $query = Kategori::where('id_pengguna', $idPengguna);

        // Filter berdasarkan tipe (MASUK/KELUAR)
        if ($tipe && $tipe !== 'semua') {
            $query->where('tipe', $tipe);
        }

        $categories = $query->orderBy('nama_kategori', 'asc')->paginate(5);

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'tipe_kategori' => 'required|in:pemasukan,pengeluaran',
        ]);

        Kategori::create([
            'id_pengguna'   => Auth::id(),
            'nama_kategori' => $request->nama_kategori,
            'tipe'          => $request->tipe_kategori == 'pemasukan' ? 'MASUK' : 'KELUAR',
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Tambahkan fungsi baru ini di KategoriController
public function show($id)
{
    $kategori = Kategori::where('id_pengguna', Auth::id())
                        ->where('id_kategori', $id)
                        ->firstOrFail();
    return response()->json($kategori);
}

// Ubah fungsi update menjadi seperti ini
public function update(Request $request, $id)
{
    $request->validate([
        'nama_kategori' => 'required|string|max:100',
        'tipe_kategori' => 'required|in:pemasukan,pengeluaran',
    ]);

    $kategori = Kategori::where('id_pengguna', Auth::id())
                        ->where('id_kategori', $id)
                        ->firstOrFail();
    
    $kategori->update([
        'nama_kategori' => $request->nama_kategori,
        'tipe'          => $request->tipe_kategori == 'pemasukan' ? 'MASUK' : 'KELUAR',
    ]);

    // Kembalikan JSON, bukan redirect
    return response()->json([
        'success' => true,
        'message' => 'Kategori berhasil diperbarui',
        'data' => $kategori
    ]);
}

    public function destroy($id)
    {
        $kategori = Kategori::where('id_pengguna', Auth::id())
                            ->where('id_kategori', $id)
                            ->firstOrFail();
        $kategori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}