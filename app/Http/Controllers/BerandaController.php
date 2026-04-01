<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekening;
use App\Models\Transaksi;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        // =========================
        // TOTAL SALDO REKENING
        // =========================
        $totalSaldo = Rekening::sum('saldo');

        // =========================
        // TOTAL PEMASUKAN BULAN INI
        // =========================
        $totalPemasukan = Transaksi::where('tipe', 'MASUK')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('jumlah');

        // =========================
        // TOTAL PENGELUARAN BULAN INI
        // =========================
        $totalPengeluaran = Transaksi::where('tipe', 'KELUAR')
            ->whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('jumlah');

        // =========================
        // PERSENTASE PIE CHART
        // =========================
        $total = $totalPemasukan + $totalPengeluaran;

        $pemasukanPercentage = $total > 0
            ? round(($totalPemasukan / $total) * 100)
            : 0;

        $pengeluaranPercentage = $total > 0
            ? round(($totalPengeluaran / $total) * 100)
            : 0;

        // =========================
        // DATA LINE CHART (PER BULAN)
        // =========================
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $dataMasuk = [];
        $dataKeluar = [];

        for ($i = 1; $i <= 12; $i++) {
            $dataMasuk[] = Transaksi::where('tipe', 'MASUK')
                ->whereMonth('tanggal_transaksi', $i)
                ->whereYear('tanggal_transaksi', $tahun)
                ->sum('jumlah');

            $dataKeluar[] = Transaksi::where('tipe', 'KELUAR')
                ->whereMonth('tanggal_transaksi', $i)
                ->whereYear('tanggal_transaksi', $tahun)
                ->sum('jumlah');
        }

        return view('beranda.beranda', compact(
            'totalSaldo',
            'totalPemasukan',
            'totalPengeluaran',
            'pemasukanPercentage',
            'pengeluaranPercentage',
            'labels',
            'dataMasuk',
            'dataKeluar'
        ));
    }
}
