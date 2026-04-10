<?php

namespace App\Http\Controllers;

use App\Models\Pengingat;
use App\Models\Rekening;
use App\Models\Kategori;
use Illuminate\Http\Request;

class PengingatController extends Controller
{
    // Halaman Daftar Pembayaran Reguler
    public function index()
    {
        $pengingats = Pengingat::with(['rekening', 'kategori'])->get();
        return view('pengingat.index', compact('pengingats'));
    }

    // Halaman Form Buat Pengingat (Sesuai Gambar UI)
    public function create()
    {
        $rekenings = Rekening::all();
        $kategoris = Kategori::all();
        return view('pengingat.create', compact('rekenings', 'kategoris'));
    }

    // Simpan Data ke Database
    public function store(Request $request)
    {
        $request->validate([
            'id_rekening' => 'required|exists:rekenings,id_rekening',
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'nama_pembayaran' => 'required|string|max:100',
            'frekuensi' => 'required',
            'tanggal_mulai' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'tipe' => 'required|in:Pemasukan,Pengeluaran'
        ]);

        // Sistem menyimpan ke tabel pengingat [cite: 62]
        Pengingat::create($request->all());

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pengingat berhasil disimpan!');
    }

    public function destroy($id)
    {
        $pengingat = Pengingat::findOrFail($id);
        $pengingat->delete();
        return redirect()->route('pembayaran.index')
            ->with('success', 'Pengingat berhasil dihapus');
    }
}
