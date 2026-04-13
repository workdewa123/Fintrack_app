<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    /**
     * Menampilkan halaman awal (Halaman Selamat Datang).
     */
    public function showHalamanAwal()
    {
        return view('halaman_awal');
    }

    /**
     * Menampilkan formulir masuk (Login).
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Memproses verifikasi kredensial pengguna (Login).
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Verifikasi username dan password
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/beranda')->with('success', 'Selamat datang!');
        }

        return back()->withErrors(['username' => 'Username atau kata sandi salah.'])->onlyInput('username');
    }

    /**
     * Menampilkan formulir pendaftaran akun (Register).
     */
    public function showRegistrationForm()
    {
        return view('register');
    }

    /**
     * Memproses pendaftaran akun pengguna baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:penggunas,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Pengguna::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }

    /**
     * Menampilkan halaman fitur aplikasi (Landing Page).
     */
    public function showLandingPage()
    {
        return view('landing_page');
    }

    /**
     * Menghapus sesi pengguna dan keluar dari aplikasi (Logout).
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /**
     * Memperbarui data profil pengguna dan unggahan foto profil.
     */
    public function updateProfil(Request $request)
    {
        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();

        $request->validate([
            'nama'        => 'required|string|max:100',
            'username'    => 'required|string|max:50|unique:penggunas,username,' . $user->id_pengguna . ',id_pengguna',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->nama = $request->nama;
        $user->username = $request->username;

        // Proses jika ada unggahan foto profil baru
        if ($request->hasFile('foto_profil')) {
            // Hapus berkas foto lama jika ada di direktori
            if ($user->foto_profil && file_exists(public_path('images/profil/' . $user->foto_profil))) {
                unlink(public_path('images/profil/' . $user->foto_profil));
            }

            // Simpan foto profil baru dengan nama unik
            $file = $request->file('foto_profil');
            $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profil'), $namaFile);

            $user->foto_profil = $namaFile;
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
