<?php

namespace App\Http\Controllers;

use App\Models\Pengingat;
use Illuminate\Http\Request;

class PengingatController extends Controller
{
    //
    public function index()
    {
        return response()->json(Pengingat::with(['rekening', 'kategori'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rekening' => 'required|exists:rekenings,id_rekening',
            'id_kategori' => 'nullable|exists:kategoris,id_kategori',
            'nama_pembayaran' => 'required|string|max:100',
            'frekuensi' => 'required|in:HARIAN,MINGGUAN,BULANAN',
            'tanggal_mulai' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $pengingat = Pengingat::create($request->all());
        return response()->json($pengingat);
    }

    public function show(Pengingat $pengingat)
    {
        return response()->json($pengingat->load(['rekening', 'kategori']));
    }

    public function update(Request $request, Pengingat $pengingat)
    {
        $pengingat->update($request->all());
        return response()->json($pengingat);
    }

    public function destroy(Pengingat $pengingat)
    {
        $pengingat->delete();
        return response()->json(['message' => 'Pengingat berhasil dihapus']);
    }
}