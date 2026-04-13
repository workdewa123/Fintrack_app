<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Rekening;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    /**
     * Mengambil semua data transaksi beserta relasi rekening dan kategori.
     * Digunakan untuk kebutuhan API umum.
     */
    public function index()
    {
        return response()->json(Transaksi::with(['rekening', 'kategori'])->get());
    }

    /**
     * Menampilkan halaman riwayat transaksi (tampilan utama).
     */
    public function halamanRiwayat()
    {
        return view('transaksi.index');
    }

    /**
     * Mengambil data transaksi dengan fitur pencarian, filter tipe,
     * statistik akumulasi, dan sistem pagination untuk tabel.
     */
    public function getTransaksiData(Request $request)
    {
        $id_pengguna = Auth::id();
        $search = $request->query('search');
        $tipe = $request->query('tipe');

        // Query dasar: Memastikan hanya mengambil transaksi milik pengguna yang login
        $baseQuery = Transaksi::with(['rekening', 'kategori'])
            ->whereHas('rekening', function ($q) use ($id_pengguna) {
                $q->where('id_pengguna', $id_pengguna);
            });

        // Menghitung statistik (Total Volume, Pemasukan, dan Pengeluaran)
        $stats = [
            'total_volume'      => (clone $baseQuery)->sum('jumlah'),
            'total_pemasukan'   => (clone $baseQuery)->where('tipe', 'MASUK')->sum('jumlah'),
            'total_pengeluaran' => (clone $baseQuery)->where('tipe', 'KELUAR')->sum('jumlah'),
        ];

        // Filter berdasarkan tipe transaksi (MASUK/KELUAR)
        if ($tipe && $tipe !== 'semua') {
            $baseQuery->where('tipe', $tipe);
        }

        // Fitur Pencarian: Mencari di kolom keterangan atau nama kategori
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->where('keterangan', 'LIKE', "%{$search}%")
                  ->orWhereHas('kategori', function($k) use ($search) {
                      $k->where('nama_kategori', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Mengurutkan berdasarkan tanggal terbaru dan membagi halaman (paginate)
        $paginatedData = $baseQuery->orderBy('tanggal_transaksi', 'desc')->paginate(5);

        // Menggabungkan data pagination dengan data statistik dalam satu respon JSON
        return response()->json(array_merge($paginatedData->toArray(), $stats));
    }

    /**
     * Menyimpan transaksi baru ke dalam database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_rekening'       => 'required|exists:rekenings,id_rekening',
            'id_kategori'       => 'nullable|exists:kategoris,id_kategori',
            'tipe'              => 'required|in:MASUK,KELUAR',
            'jumlah'            => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        $transaksi = Transaksi::create($request->all());
        return response()->json($transaksi);
    }

    /**
     * Mengunduh laporan riwayat transaksi dalam format PDF.
     */
    public function exportPDF()
    {
        $id_pengguna = Auth::id();

        // Mengambil seluruh transaksi milik pengguna untuk dicetak
        $transaksi = Transaksi::with(['rekening', 'kategori'])
            ->whereHas('rekening', function ($q) use ($id_pengguna) {
                $q->where('id_pengguna', $id_pengguna);
            })
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        // Merender tampilan ke dalam bentuk PDF
        $pdf = Pdf::loadView('laporan.transaksi_pdf', compact('transaksi'));

        // Mengirimkan file PDF untuk diunduh otomatis
        return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Menampilkan detail satu transaksi tertentu dalam format JSON.
     */
    public function show(Transaksi $transaksi)
    {
        return response()->json($transaksi->load(['rekening', 'kategori']));
    }

    /**
     * Memperbarui data transaksi dan melakukan penyesuaian otomatis pada saldo rekening.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        // 1. Ambil data rekening lama untuk mengembalikan saldo sebelum diedit
        $rekeningLama = Rekening::find($transaksi->id_rekening);

        // 2. Logika "Rollback": Batalkan pengaruh transaksi lama pada saldo
        if ($transaksi->tipe == 'MASUK') {
            $rekeningLama->saldo -= $transaksi->jumlah;
        } else {
            $rekeningLama->saldo += $transaksi->jumlah;
        }
        $rekeningLama->save();

        // 3. Update data transaksi dengan inputan yang baru
        $transaksi->update($request->all());

        // 4. Ambil data rekening (mungkin rekening berubah) dan terapkan saldo baru
        $rekeningBaru = Rekening::find($request->id_rekening);
        if ($request->tipe == 'MASUK') {
            $rekeningBaru->saldo += $request->jumlah;
        } else {
            $rekeningBaru->saldo -= $request->jumlah;
        }
        $rekeningBaru->save();

        return response()->json($transaksi);
    }

    /**
     * Menghapus transaksi dari database.
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }
}
