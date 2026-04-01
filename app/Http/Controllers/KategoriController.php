<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Menampilkan halaman daftar kategori dalam bentuk tabel.
     */
    public function index()
    {
        // Mengambil semua data kategori milik pengguna yang sedang login
        // Sesuaikan 'id_pengguna' dengan nama kolom foreign key di tabel kategori Anda
        $categories = Kategori::where('id_pengguna', Auth::id())->get();

        // Mengirimkan data ke view kategori.blade.php
        return view('kategori.kategori', compact('categories'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input sesuai dengan atribut 'name' di tambah_kategori.blade.php
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'tipe_kategori' => 'required|in:pemasukan,pengeluaran',
        ]);

        // Simpan ke database
        Kategori::create([
            'id_pengguna'   => Auth::id(),
            'nama_kategori' => $request->nama_kategori,
            'tipe'          => $request->tipe_kategori == 'pemasukan' ? 'MASUK' : 'KELUAR',
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Memperbarui data kategori yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'tipe_kategori' => 'required|in:pemasukan,pengeluaran',
        ]);

        $kategori = Kategori::findOrFail($id);
        
        // Update data
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'tipe'          => $request->tipe_kategori == 'pemasukan' ? 'MASUK' : 'KELUAR',
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}