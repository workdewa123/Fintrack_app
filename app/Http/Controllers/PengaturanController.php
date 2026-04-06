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
            $pengaturan->mata_uang = 'IDR-Rupiah';
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = User::find(Auth::id());
        if ($user) {
            $user->name = $request->nama;

            if ($request->hasFile('foto')) {
                if ($user->foto_profil && File::exists(public_path('images/profil/' . $user->foto_profil))) {
                    File::delete(public_path('images/profil/' . $user->foto_profil));
                }
                $fileName = time() . '_' . $user->id . '.' . $request->foto->extension();
                $request->foto->move(public_path('images/profil'), $fileName);
                $user->foto_profil = $fileName;
            }
            $user->save();
            return back()->with('success', 'Profil berhasil diperbarui!');
        }
        return back()->with('error', 'Gagal memperbarui profil.');
    }

    public function updatePreferensi(Request $request)
    {
        $pengaturan = \App\Models\Pengaturan::where('user_id', Auth::id())->first();

        if ($pengaturan) {
            $pengaturan->update([
                'bahasa' => $request->bahasa,
                'mata_uang' => $request->mata_uang ?? $pengaturan->mata_uang,
            ]);

            // Simpan ke session agar layout bisa baca
            session(['locale' => ($request->bahasa == 'English' ? 'en' : 'id')]);

            return back()->with('success', 'Bahasa berhasil diubah ke ' . $request->bahasa);
        }
        return back()->with('error', 'Gagal.');
    }

    public function updatePin(Request $request)
    {
        $request->validate(['pin' => 'required|digits:6']);

        $user = \App\Models\User::find(Auth::id());
        // Kita simpan di kolom password (atau kolom 'pin' jika kamu punya di tabel users)
        $user->password = bcrypt($request->pin);
        $user->save();

        return redirect()->route('pengaturan.index')->with('success', 'PIN Keamanan Berhasil Disimpan!');
    }
}