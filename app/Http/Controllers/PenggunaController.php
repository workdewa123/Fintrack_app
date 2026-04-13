<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    // Method untuk menampilkan halaman awal (Landing Page 1)
    public function showHalamanAwal()
    {
        return view('halaman_awal');
    }

    // Method untuk menampilkan halaman login
    public function showLoginForm()
    {
        return view('login');
    }

    // Method untuk memproses login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/beranda')->with('success', 'Selamat datang!');
        }

        return back()->withErrors(['username' => 'Username atau kata sandi salah.'])->onlyInput('username');
    }

    // Method untuk menampilkan halaman registrasi
    public function showRegistrationForm()
    {
        return view('register');
    }

    // Method untuk memproses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:penggunas,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }

    // Method untuk menampilkan halaman landing page (halaman fitur)
    public function showLandingPage()
    {
        return view('landing_page');
    }

    // Method untuk logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    public function updateProfil(Request $request)
    {
        // Menggunakan Auth::user() untuk mendapatkan model user yang sedang login
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:penggunas,username,' . $user->id_pengguna . ',id_pengguna',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->nama = $request->nama;
        $user->username = $request->username;

        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
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
}
