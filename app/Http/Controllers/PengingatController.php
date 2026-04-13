<?php

namespace App\Http\Controllers;

use App\Models\Pengingat;
use App\Models\Rekening;
use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengingatController extends Controller
{
    /**
     * Menampilkan halaman daftar pengingat pembayaran reguler.
     */
    public function index()
    {
        return view('pengingat.index');
    }

    /**
     * Mengambil data pengingat dalam format JSON untuk tabel (API).
     */
    public function getPengingatData(Request $request)
    {
        $search = $request->query('search');
        $frequency = $request->query('frekuensi');

        $query = Pengingat::where('id_pengguna', Auth::id())->with(['rekening', 'kategori']);

        // Filter berdasarkan kata kunci nama pembayaran
        if ($search) {
            $query->where('nama_pembayaran', 'LIKE', "%{$search}%");
        }

        // Filter berdasarkan frekuensi tagihan
        if ($frequency && $frequency !== 'semua') {
            $query->where('frekuensi', strtoupper($frequency));
        }

        $pengingats = $query->paginate(5);

        return response()->json($pengingats);
    }

    /**
     * Menampilkan halaman formulir pembuatan pengingat baru.
     */
    public function create()
    {
        $rekenings = Rekening::where('id_pengguna', Auth::id())->get();
        $kategoris = Kategori::all();
        return view('pengingat.create', compact('rekenings', 'kategoris'));
    }

    /**
     * Menyimpan data pengingat baru ke database.
     */
    public function store(Request $request)
    {
        // Tetapkan tipe default sebagai 'Pengeluaran' jika tidak ada
        if (!$request->has('tipe')) {
            $request->merge(['tipe' => 'Pengeluaran']);
        }

        $validated = $request->validate([
            'id_rekening'     => 'required|exists:rekenings,id_rekening',
            'id_kategori'     => 'required|exists:kategoris,id_kategori',
            'nama_pembayaran' => 'required|string|max:100',
            'frekuensi'       => 'required|in:HARIAN,MINGGUAN,BULANAN',
            'detail_jadwal'   => 'nullable|required_if:frekuensi,MINGGUAN,BULANAN|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_akhir'   => 'nullable|date|after_or_equal:tanggal_mulai',
            'jumlah'          => 'required|numeric|min:0',
            'tipe'            => 'required|in:Pengeluaran',
            'komentar'        => 'nullable|string'
        ]);

        try {
            $validated['id_pengguna'] = Auth::id();
            Pengingat::create($validated);
            return response()->json(['success' => true, 'message' => 'Pengingat berhasil disimpan!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil data detail pengingat untuk ditampilkan di modal.
     */
    public function show($id)
    {
        $pengingat = Pengingat::where('id_pengguna', Auth::id())
            ->with(['kategori', 'rekening'])
            ->findOrFail($id);

        return response()->json($pengingat);
    }

    /**
     * Memperbarui data pengingat yang sudah ada.
     */
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
            'frekuensi'       => 'required|in:HARIAN,MINGGUAN,BULANAN',
            'detail_jadwal'   => 'nullable|required_if:frekuensi,MINGGUAN,BULANAN|integer',
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

    /**
     * Menghapus data pengingat.
     */
    public function destroy($id)
    {
        $pengingat = Pengingat::findOrFail($id);
        $pengingat->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Pengingat berhasil dihapus');
    }

    /**
     * Memproses konfirmasi pembayaran dari pengingat ke tabel transaksi.
     */
    public function konfirmasiBayar($id)
    {
        $pengingat = Pengingat::findOrFail($id);

        DB::beginTransaction();
        try {
            // 1. Buat catatan transaksi pengeluaran
            Transaksi::create([
                'id_rekening'       => $pengingat->id_rekening,
                'id_kategori'       => $pengingat->id_kategori,
                'jumlah'            => $pengingat->jumlah,
                'tanggal_transaksi' => now(),
                'keterangan'        => 'Pembayaran Rutin: ' . $pengingat->nama_pembayaran,
                'tipe'              => 'KELUAR'
            ]);

            // 2. Kurangi saldo pada rekening terkait
            $rekening = Rekening::find($pengingat->id_rekening);
            $rekening->saldo -= $pengingat->jumlah;
            $rekening->save();

            // 3. Perbarui riwayat tanggal bayar terakhir pada pengingat
            $pengingat->tanggal_bayar_terakhir = now()->toDateString();
            $pengingat->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dikonfirmasi!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal: ' . $e->getMessage()]);
        }
    }
}
