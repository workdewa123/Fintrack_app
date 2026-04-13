<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class PengaturanController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id(); // Ambil ID yang sedang login

        // Cari data pengaturan berdasarkan user_id
        $pengaturan = Pengaturan::where('user_id', $userId)->first();

        // FIX ERROR: Jika tidak ada, buat manual (jangan pakai firstOrCreate dulu biar aman)
        if (!$pengaturan) {
            $pengaturan = new Pengaturan();
            $pengaturan->user_id = $userId; // Paksa isi di sini
            $pengaturan->format_tanggal = 'DD/MM/YYYY';
            $pengaturan->bahasa = 'Indonesia';
            $pengaturan->notifikasi_aktif = false;
            $pengaturan->save();
        }

        return view('pengaturan.index', [
            'pengaturan' => $pengaturan,
            'user' => Auth::user()
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = \App\Models\Pengguna::findOrFail(Auth::id()); //

        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:penggunas,username,' . $user->id_pengguna . ',id_pengguna',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $user->nama = $request->nama;
        $user->username = $request->username;

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika bukan default dan file-nya ada
            if ($user->foto_profil && file_exists(public_path('images/profil/' . $user->foto_profil))) {
                unlink(public_path('images/profil/' . $user->foto_profil));
            }

            // Upload foto baru
            $file = $request->file('foto_profil');
            $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profil'), $namaFile);

            $user->foto_profil = $namaFile;
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    public function updatePreferensi(Request $request)
    {
        $pengaturan = \App\Models\Pengaturan::where('user_id', Auth::id())->first();

        if ($pengaturan) {
            $pengaturan->update([
                'bahasa' => $request->bahasa,
            ]);

            // Simpan ke session agar layout bisa baca
            session(['locale' => ($request->bahasa == 'English' ? 'en' : 'id')]);

            return back()->with('success', 'Bahasa berhasil diubah ke ' . $request->bahasa);
        }
        return back()->with('error', 'Gagal.');
    }
}
