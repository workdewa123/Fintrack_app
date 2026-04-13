<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekening;
use App\Models\Transaksi;
use App\Models\Pengingat;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard (Beranda).
     */
    public function index()
    {
        $id_pengguna = Auth::id();
        $bulan = now()->month;
        $tahun = now()->year;

        // Hitung total saldo dari seluruh rekening milik pengguna yang login
        $totalSaldo = Rekening::where('id_pengguna', $id_pengguna)->sum('saldo');

        // Hitung total pemasukan pengguna pada bulan dan tahun berjalan
        $totalPemasukan = Transaksi::whereHas('rekening', function ($q) use ($id_pengguna) {
            $q->where('id_pengguna', $id_pengguna);
        })
        ->where('tipe', 'MASUK')
        ->whereMonth('tanggal_transaksi', $bulan)
        ->whereYear('tanggal_transaksi', $tahun)
        ->sum('jumlah');

        // Hitung total pengeluaran pengguna pada bulan dan tahun berjalan
        $totalPengeluaran = Transaksi::whereHas('rekening', function ($q) use ($id_pengguna) {
            $q->where('id_pengguna', $id_pengguna);
        })
        ->where('tipe', 'KELUAR')
        ->whereMonth('tanggal_transaksi', $bulan)
        ->whereYear('tanggal_transaksi', $tahun)
        ->sum('jumlah');

        // Ambil data pengingat tagihan yang aktif/dimulai pada bulan ini
        $tagihanMendatang = Pengingat::whereHas('rekening', function ($q) use ($id_pengguna) {
            $q->where('id_pengguna', $id_pengguna);
        })
        ->whereMonth('tanggal_mulai', '<=', $bulan)
        ->get();

        return view('beranda.beranda', compact('totalSaldo', 'totalPemasukan', 'totalPengeluaran', 'tagihanMendatang'));
    }

    /**
     * Menyimpan transaksi baru dan memperbarui saldo rekening terkait.
     */
    public function storeTransaksi(Request $request)
    {
        try {
            // Validasi input transaksi
            $request->validate([
                'id_rekening'       => 'required',
                'id_kategori'       => 'required',
                'tipe'              => 'required|in:MASUK,KELUAR',
                'jumlah'            => 'required|numeric|min:0',
                'tanggal_transaksi' => 'required',
            ]);

            // Simpan data ke tabel transaksi
            $transaksi = Transaksi::create([
                'id_rekening'       => $request->id_rekening,
                'id_kategori'       => $request->id_kategori,
                'tipe'              => $request->tipe,
                'jumlah'            => $request->jumlah,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan'        => $request->catatan,
            ]);

            // Perbarui saldo pada tabel rekening berdasarkan tipe transaksi
            $rekening = Rekening::findOrFail($request->id_rekening);
            if ($request->tipe == 'MASUK') {
                $rekening->saldo += $request->jumlah;
            } else {
                $rekening->saldo -= $request->jumlah;
            }
            $rekening->save();

            return response()->json(['success' => true, 'data' => $transaksi]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil data dashboard dalam format JSON untuk kebutuhan grafik.
     */
    public function getApiData()
    {
        $id_pengguna = Auth::id();
        $bulan = now()->month;
        $tahun = now()->year;

        // Menyiapkan data pemasukan dan pengeluaran per bulan selama satu tahun
        $dataMasuk = [];
        $dataKeluar = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[] = Transaksi::whereHas('rekening', function ($q) use ($id_pengguna) {
                $q->where('id_pengguna', $id_pengguna);
            })
            ->where('tipe', 'MASUK')
            ->whereMonth('tanggal_transaksi', $i)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('jumlah');

            $dataKeluar[] = Transaksi::whereHas('rekening', function ($q) use ($id_pengguna) {
                $q->where('id_pengguna', $id_pengguna);
            })
            ->where('tipe', 'KELUAR')
            ->whereMonth('tanggal_transaksi', $i)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('jumlah');
        }

        return response()->json([
            'totalSaldo'       => Rekening::where('id_pengguna', $id_pengguna)->sum('saldo'),
            'totalPemasukan'   => Transaksi::whereHas('rekening', function ($q) use ($id_pengguna) {
                $q->where('id_pengguna', $id_pengguna);
            })->where('tipe', 'MASUK')->whereMonth('tanggal_transaksi', $bulan)->sum('jumlah'),
            'totalPengeluaran' => Transaksi::whereHas('rekening', function ($q) use ($id_pengguna) {
                $q->where('id_pengguna', $id_pengguna);
            })->where('tipe', 'KELUAR')->whereMonth('tanggal_transaksi', $bulan)->sum('jumlah'),
            'chartLine'        => [
                'masuk'  => $dataMasuk,
                'keluar' => $dataKeluar
            ]
        ]);
    }
}
