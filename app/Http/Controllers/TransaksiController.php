<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    //
    public function index()
    {
        return response()->json(Transaksi::with(['rekening', 'kategori'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rekening' => 'required|exists:rekenings,id_rekening',
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'tipe' => 'required|in:MASUK,KELUAR',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        $transaksi = Transaksi::create($request->all());
        return response()->json($transaksi);
    }

    public function show(Transaksi $transaksi)
    {
        return response()->json($transaksi->load(['rekening', 'kategori']));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $transaksi->update($request->all());
        return response()->json($transaksi);
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }
}