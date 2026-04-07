<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

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

    public function exportPDF()
    {
        $id_pengguna = Auth::id();
        
        // Ambil data transaksi milik user yang sedang login
        $transaksi = Transaksi::with(['rekening', 'kategori'])
            ->whereHas('rekening', function ($q) use ($id_pengguna) {
                $q->where('id_pengguna', $id_pengguna);
            })
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        // Load view khusus untuk PDF
        $pdf = Pdf::loadView('laporan.transaksi_pdf', compact('transaksi'));

        // Unduh file PDF
        return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d') . '.pdf');
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