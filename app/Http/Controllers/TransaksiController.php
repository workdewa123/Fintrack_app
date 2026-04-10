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

    // app/Http/Controllers/TransaksiController.php

    public function halamanRiwayat()
    {
        return view('transaksi.index'); // Kita akan buat file ini
    }

    // app/Http/Controllers/TransaksiController.php

    public function getTransaksiData(Request $request)
{
    $id_pengguna = Auth::id();
    $search = $request->query('search');
    $tipe = $request->query('tipe');

    $baseQuery = Transaksi::with(['rekening', 'kategori'])
        ->whereHas('rekening', function ($q) use ($id_pengguna) {
            $q->where('id_pengguna', $id_pengguna);
        });

    // Statistik tetap aman...
    $stats = [
        'total_volume' => (clone $baseQuery)->sum('jumlah'),
        'total_pemasukan' => (clone $baseQuery)->where('tipe', 'MASUK')->sum('jumlah'),
        'total_pengeluaran' => (clone $baseQuery)->where('tipe', 'KELUAR')->sum('jumlah'),
    ];

    if ($tipe && $tipe !== 'semua') {
        $baseQuery->where('tipe', $tipe);
    }

    // --- PERBAIKAN DI SINI ---
    if ($search) {
        $baseQuery->where(function($q) use ($search) {
            // Ganti 'catatan' menjadi 'keterangan'
            $q->where('keterangan', 'LIKE', "%{$search}%") 
              ->orWhereHas('kategori', function($k) use ($search) {
                  $k->where('nama_kategori', 'LIKE', "%{$search}%");
              });
        });
    }

    $paginatedData = $baseQuery->orderBy('tanggal_transaksi', 'desc')->paginate(5);
    
    return response()->json(array_merge($paginatedData->toArray(), $stats));
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
        // 1. Ambil data rekening lama untuk penyesuaian saldo
        $rekeningLama = \App\Models\Rekening::find($transaksi->id_rekening);
        
        // 2. Kembalikan saldo sebelum transaksi ini terjadi
        if ($transaksi->tipe == 'MASUK') {
            $rekeningLama->saldo -= $transaksi->jumlah;
        } else {
            $rekeningLama->saldo += $transaksi->jumlah;
        }
        $rekeningLama->save();

        // 3. Update Data Transaksi
        $transaksi->update($request->all());

        // 4. Terapkan saldo baru berdasarkan data yang baru diupdate
        $rekeningBaru = \App\Models\Rekening::find($request->id_rekening);
        if ($request->tipe == 'MASUK') {
            $rekeningBaru->saldo += $request->jumlah;
        } else {
            $rekeningBaru->saldo -= $request->jumlah;
        }
        $rekeningBaru->save();

        return response()->json($transaksi);
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }
}