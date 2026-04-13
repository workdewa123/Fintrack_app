<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Menampilkan halaman utama kategori.
     */
    public function index()
    {
        return view('kategori.kategori');
    }

    /**
     * Mengambil data kategori untuk tabel dengan fitur filter dan pagination.
     */
    public function getKategoriData(Request $request)
    {
        $idPengguna = Auth::id();
        $tipe = $request->query('tipe');

        $query = Kategori::where('id_pengguna', $idPengguna);

        // Filter berdasarkan tipe (MASUK/KELUAR) jika ada
        if ($tipe && $tipe !== 'semua') {
            $query->where('tipe', $tipe);
        }

        $categories = $query->orderBy('nama_kategori', 'asc')->paginate(5);

        return response()->json($categories);
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'tipe_kategori' => 'required|in:pemasukan,pengeluaran',
        ]);

        $kategori = Kategori::create([
            'id_pengguna'   => Auth::id(),
            'nama_kategori' => $request->nama_kategori,
            'tipe'          => $request->tipe_kategori == 'pemasukan' ? 'MASUK' : 'KELUAR',
        ]);

        // Respon JSON untuk permintaan AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Kategori berhasil ditambahkan!',
                'data'    => $kategori
            ]);
        }

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Mengambil detail satu kategori dalam format JSON.
     */
    public function show($id)
    {
        $kategori = Kategori::where('id_pengguna', Auth::id())
            ->where('id_kategori', $id)
            ->firstOrFail();

        return response()->json($kategori);
    }

    /**
     * Memperbarui data kategori yang sudah ada.
     */
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

        return response()->json([
            'status'  => 'success',
            'message' => 'Kategori berhasil diperbarui',
            'data'    => $kategori
        ]);
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy($id)
    {
        try {
            $kategori = Kategori::where('id_pengguna', Auth::id())
                ->where('id_kategori', $id)
                ->firstOrFail();

            $kategori->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Kategori berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil seluruh data kategori tanpa pagination untuk kebutuhan dropdown.
     */
    public function allKategori()
    {
        $categories = Kategori::where('id_pengguna', Auth::id())
            ->orderBy('nama_kategori', 'asc')
            ->get();

        return response()->json($categories);
    }
}
