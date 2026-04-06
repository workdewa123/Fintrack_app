<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index()
    {
        // Ambil data asli dari DB berdasarkan user login
        $categories = Kategori::where('id_pengguna', Auth::id())->get();
        return view('kategori.kategori', compact('categories'));
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

    // Bagian Update
public function update(Request $request, $id)
{
    // Pastikan mencari berdasarkan 'id_kategori'
    $kategori = Kategori::where('id_pengguna', Auth::id())
                        ->where('id_kategori', $id)
                        ->firstOrFail();
    
    $kategori->update([
        'nama_kategori' => $request->nama_kategori,
        'tipe'          => $request->tipe_kategori == 'pemasukan' ? 'MASUK' : 'KELUAR',
    ]);

    return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
}

// Bagian Destroy
public function destroy($id)
{
    $kategori = Kategori::where('id_pengguna', Auth::id())
                        ->where('id_kategori', $id)
                        ->firstOrFail();
    $kategori->delete();

    return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
}
}