<?php

namespace App\Http\Controllers;

use App\Models\Pengingat;
use App\Models\Rekening;
use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengingatController extends Controller
{
    // Halaman Daftar Pembayaran Reguler
    public function index()
    {
        return view('pengingat.index'); // Langsung return view, data akan di-load via JS
    }

    // API Data untuk Tabel (Metode yang dipanggil JavaScript)
    public function getPengingatData(Request $request)
    {
        $search = $request->query('search');
        $frequency = $request->query('frekuensi');

        $query = Pengingat::with(['rekening', 'kategori']);

        // Filter Pencarian
        if ($search) {
            $query->where('nama_pembayaran', 'LIKE', "%{$search}%");
        }

        // Filter Frekuensi (Semua, Harian, Mingguan, Bulanan)
        if ($frequency && $frequency !== 'semua') {
            $query->where('frekuensi', strtoupper($frequency)); // Menyamakan dengan enum HARIAN, MINGGUAN, BULANAN
        }

        // Paginate hasil agar konsisten dengan script JavaScript
        $pengingats = $query->paginate(5);

        return response()->json($pengingats);
    }    // Halaman Form Buat Pengingat (Sesuai Gambar UI)
    public function create()
    {
        $rekenings = Rekening::all();
        $kategoris = Kategori::all();
        return view('pengingat.create', compact('rekenings', 'kategoris'));
    }

    // Simpan Data ke Database
   public function store(Request $request)
    {
        // Tambahkan default tipe ke request jika tidak terkirim (opsional, untuk keamanan)
        if (!$request->has('tipe')) {
            $request->merge(['tipe' => 'Pengeluaran']);
        }

        $validated = $request->validate([
            'id_rekening'     => 'required|exists:rekenings,id_rekening',
            'id_kategori'     => 'required|exists:kategoris,id_kategori', // Dibuat required karena ini pembayaran rutin
            'nama_pembayaran' => 'required|string|max:100',
            'frekuensi'       => 'required|in:HARIAN,MINGGUAN,BULANAN',
            'detail_jadwal' => 'nullable|required_if:frekuensi,MINGGUAN,BULANAN|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_akhir'   => 'nullable|date|after_or_equal:tanggal_mulai',
            'jumlah'          => 'required|numeric|min:0',
            'tipe'            => 'required|in:Pengeluaran', // Kunci hanya untuk Pengeluaran
            'komentar'        => 'nullable|string'
        ]);
        try {
            Pengingat::create($validated);
            return response()->json(['success' => true, 'message' => 'Pengingat berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Tambahkan ini di PengingatController.php

    // 1. Ambil data detail untuk Modal Detail & Edit
    public function show($id)
    {
        // Mengambil data pengingat beserta relasi kategori dan rekeningnya
        $pengingat = Pengingat::with(['kategori', 'rekening'])->find($id);

        if (!$pengingat) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $pengingat = Pengingat::with(['rekening', 'kategori'])->findOrFail($id);
        return response()->json($pengingat);
    }

    // 2. Proses update data dari Modal Edit
    public function update(Request $request, $id)
    {
        $pengingat = Pengingat::find($id);

        if (!$pengingat) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'id_rekening'     => 'required|exists:rekenings,id_rekening',
            'id_kategori'     => 'required|exists:kategoris,id_kategori',
            'nama_pembayaran' => 'required|string|max:100',
            'frekuensi'     => 'required|in:HARIAN,MINGGUAN,BULANAN',
            'detail_jadwal' => 'nullable|required_if:frekuensi,MINGGUAN,BULANAN|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_akhir'   => 'nullable|date|after_or_equal:tanggal_mulai',
            'jumlah'          => 'required|numeric|min:0',
            'tipe'            => 'required|in:Pengeluaran',
            'komentar'        => 'nullable|string'
        ]);

        try {
            $pengingat->update($validated);
            return response()->json(['success' => true, 'message' => 'Pengingat berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $pengingat = Pengingat::findOrFail($id);
        $pengingat->delete();
        return redirect()->route('pembayaran.index')
            ->with('success', 'Pengingat berhasil dihapus');
    }

    public function konfirmasiBayar($id)
    {
        $pengingat = \App\Models\Pengingat::findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Simpan ke tabel Transaksi
            \App\Models\Transaksi::create([
                'id_rekening' => $pengingat->id_rekening,
                'id_kategori' => $pengingat->id_kategori,
                'jumlah' => $pengingat->jumlah,
                'tanggal_transaksi' => now(),
                'keterangan' => 'Pembayaran Rutin: ' . $pengingat->nama_pembayaran,
                'tipe' => 'KELUAR'
            ]);

            // 2. Potong Saldo Rekening
            $rekening = \App\Models\Rekening::find($pengingat->id_rekening);
            $rekening->saldo -= $pengingat->jumlah;
            $rekening->save();

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dikonfirmasi!']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }
}
