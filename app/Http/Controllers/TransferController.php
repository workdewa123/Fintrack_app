<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function index(Request $request)
    {
        $rekeningId = $request->query('rekening_id');
        $rekening = null;

        if ($rekeningId) {
            $rekening = Rekening::find($rekeningId);

            // Ambil transfer yang terkait dengan rekening (baik sebagai asal atau tujuan)
            $transfers = Transfer::where('id_rekening_asal', $rekeningId)
                ->orWhere('id_rekening_tujuan', $rekeningId)
                ->with(['rekeningAsal', 'rekeningTujuan'])
                ->orderBy('tanggal_transfer', 'desc')
                ->get();
        } else {
            // Jika tidak ada rekening_id, ambil semua transfer user
            $userRekeningIds = Rekening::where('id_pengguna', Auth::id())->pluck('id_rekening');
            $transfers = Transfer::whereIn('id_rekening_asal', $userRekeningIds)
                ->orWhereIn('id_rekening_tujuan', $userRekeningIds)
                ->with(['rekeningAsal', 'rekeningTujuan'])
                ->orderBy('tanggal_transfer', 'desc')
                ->get();
        }

        // Hitung total masuk dan keluar
        $totalMasuk = 0;
        $totalKeluar = 0;

        foreach ($transfers as $transfer) {
            if ($transfer->id_rekening_tujuan == $rekeningId) {
                $totalMasuk += $transfer->jumlah;
            } else {
                $totalKeluar += $transfer->jumlah;
            }
        }

        return view('riwayat_transfer', compact('rekening', 'transfers', 'totalMasuk', 'totalKeluar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rekening_asal' => 'required|exists:rekenings,id_rekening',
            'id_rekening_tujuan' => 'required|exists:rekenings,id_rekening',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_transfer' => 'required|date',
        ]);

        $transfer = Transfer::create($request->all());
        return response()->json($transfer);
    }

    public function show(Transfer $transfer)
    {
        return response()->json($transfer->load(['rekeningAsal', 'rekeningTujuan']));
    }

    public function update(Request $request, Transfer $transfer)
    {
        $transfer->update($request->all());
        return response()->json($transfer);
    }

    public function destroy(Transfer $transfer)
    {
        $transfer->delete();
        return response()->json(['message' => 'Transfer berhasil dihapus']);
    }
}
