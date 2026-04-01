@extends('layout.biasa')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
        background-color: #EAF3FF;
    }

    .auth-container {
        display: flex;
        min-height: 100vh;
    }

    .auth-left {
        flex: 1.5;
        background-color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem;
        text-align: center;
    }

    .auth-right {
        flex: 1;
        padding: 4rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .main-title {
        font-family: 'Montserrat', sans-serif;
        font-weight: bold;
        font-size: 2.25rem;
    }

    .main-title .first-word {
        color: #598EFF;
    }

    .main-title .second-phrase {
        color: #1D4ED8;
    }

    .main-heading {
        color: #1F2937;
        font-family: 'Montserrat', sans-serif;
        font-weight: bold;
        font-size: 3rem;
        text-align: center;
    }

    .tagline-text {
        color: #2260FF;
        font-size: 1rem;
    }

    .text-muted {
        color: #1F2937;
        font-size: 1.5rem;
        text-align: center;
    }

    .input-group-text {
        background-color: #e2e8f0;
        border: none;
        border-radius: 0.5rem 0 0 0.5rem;
    }

    .form-control {
        border-radius: 0 0.5rem 0.5rem 0;
    }

    /* Tambahan style untuk menata posisi gambar */
    .gambar-utama {
        filter: drop-shadow(0 15px 15px rgba(0, 0, 0, 0.1));
        margin-bottom: 20px;
        /* Jarak antara gambar utama dan elemen di bawahnya */
    }

    .gambar-hiasan {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .garis-bawah {
        margin-bottom: 20px;
        /* Jarak antara garis dan lingkaran */
    }

    .bulat-hiasan {
        margin-bottom: 40px;
    }
</style>

<div class="auth-container">
    <div class="auth-left">
        <img src="{{ asset('foto/Gambar Jabat Tangan.png') }}" alt="Ilustrasi Login" class="gambar-utama" style="width: 100%; max-width: 400px;">
        <div class="gambar-hiasan">
            <img src="{{ asset('foto/Garis Bawah Gambar.png') }}" alt="Ilustrasi Login" class="garis-bawah" style="max-width: 250px;">
            <img src="{{ asset('foto/Hiasan Bulat 2.png') }}" alt="Ilustrasi Login" class="bulat-hiasan" style="max-width: 250px;">
        </div>

        <h2 class="mt-4 main-title">
            <span class="first-word">Ayo Mulai</span>
            <span class="second-phrase">Kelola Keuanganmu</span>
        </h2>
        <p class="tagline-text">Pantau dan Atur Pengeluaran Harianmu Disini</p>
    </div>
    <div class="auth-right">
        <h2 class="main-heading">Selamat Datang</h2>
        <p class="text-muted mb-4">Atur Keuanganmu dengan Mudah dan Cepat</p>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            {{-- Bagian untuk menampilkan pesan error --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Bagian untuk menampilkan pesan sukses --}}
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required>
                    <button class="btn btn-outline-secondary password-toggle-btn" type="button" id="togglePassword">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-4">
                <a href="#" class="text-primary text-decoration-none">Lupa Kata Sandi?</a>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Masuk</button>
        </form>
        <p class="text-center mt-3">Belum Punya Akun? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Daftar Sekarang!</a></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            // Mengganti tipe input antara 'password' dan 'text'
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Mengganti ikon mata
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    });
</script>
@endsection