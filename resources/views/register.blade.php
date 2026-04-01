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
        flex: 1;
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
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .main-heading {
        color: #2c3e50;
        font-family: 'Montserrat', sans-serif;
        font-weight: bold;
        font-size: 2.5rem;
        text-align: center;
    }

    .tagline-text {
        color: #2c3e50;
        font-size: 1rem;
        margin-bottom: 2rem;
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

    .btn-primary {
        background-color: #3b82f6;
        border: none;
        border-radius: 0.5rem;
        font-size: 1rem;
    }

    .text-muted {
        color: #888;
    }

    .gambar-utama {
        max-width: 400px;
        width: 100%;
        filter: drop-shadow(0 15px 15px rgba(0, 0, 0, 0.1));
        margin-bottom: 20px;
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
    {{-- Bagian Kiri --}}
    <div class="auth-left">
        <img src="{{ asset('foto/Gambar Jabat Tangan.png') }}" alt="Ilustrasi Register" class="gambar-utama">
        <div class="gambar-hiasan">
            <img src="{{ asset('foto/Garis Bawah Gambar.png') }}" alt="Ilustrasi Login" class="garis-bawah" style="max-width: 250px;">
            <img src="{{ asset('foto/Hiasan Bulat 2.png') }}" alt="Ilustrasi Login" class="bulat-hiasan" style="max-width: 250px;">
        </div>
        <h2 class="mt-4" style="font-family: 'Montserrat', sans-serif; font-weight: bold; font-size: 2rem;">
            <span style="color:#598EFF;">Daftar</span>
            <span style="color:#1D4ED8;">Sekarang</span>
        </h2>
    </div>

    {{-- Bagian Kanan --}}
    <div class="auth-right">
        <h2 class="main-heading">Daftar</h2>
        <p class="tagline-text">Buat Akun untuk Melacak Keuangan Pribadi</p>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            {{-- Error message --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Nama Lengkap --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-circle-user"></i></span>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                </div>
            </div>

            {{-- Username --}}
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan kata sandi" required>
                    <button class="btn btn-outline-secondary password-toggle-btn" type="button" id="togglePassword">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control password-input" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                    <button class="btn btn-outline-secondary password-toggle-btn" type="button" id="toggleConfirmPassword">
                        <i class="fa-solid fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Daftar</button>
        </form>

        <p class="text-center mt-3">Sudah Punya Akun?
            <a href="{{ route('login') }}" class="text-primary text-decoration-none">Masuk!</a>
        </p>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mendapatkan elemen tombol dan input password
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        const toggleConfirmPasswordBtn = document.getElementById('toggleConfirmPassword');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        // Fungsi untuk toggle password
        function toggleVisibility(button, input) {
            button.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }

        // Panggil fungsi untuk setiap pasangan input dan tombol
        toggleVisibility(togglePasswordBtn, passwordInput);
        toggleVisibility(toggleConfirmPasswordBtn, confirmPasswordInput);
    });
</script>
@endsection