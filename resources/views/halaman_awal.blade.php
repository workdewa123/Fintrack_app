@extends('layout.biasa')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Montserrat:wght@800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #ffffff;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .hero-section {
        display: flex;
        align-items: center;
        min-height: 100vh;
        padding: 2rem;
        background-color: #ffffff;
        /* Putih Bersih */
        flex-wrap: wrap;
        position: relative;
        overflow: hidden;
    }

    /* Dekorasi Elemen Halus (Aksen Biru Lembut) */
    .hero-section::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
        top: -100px;
        right: -100px;
        z-index: 0;
    }

    .hero-left {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        z-index: 1;
    }

    .hero-right {
        flex: 1;
        padding: 0 5rem;
        text-align: left;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        z-index: 2;
    }

    .hero-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 5rem;
        font-weight: 800;
        color: #1E3A8A;
        /* Biru Gelap FinTrack */
        margin-bottom: 0.5rem;
        line-height: 1.1;
        letter-spacing: -3px;
    }

    .hero-subtitle {
        font-size: 1.75rem;
        color: #3B82F6;
        /* Biru Aksen */
        margin-bottom: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .hero-tagline {
        font-size: 1.1rem;
        color: #64748B;
        /* Slate Gray agar nyaman dibaca */
        margin-bottom: 3rem;
        line-height: 1.8;
        max-width: 500px;
    }

    .btn-action {
        width: 250px;
        padding: 1.1rem 2rem;
        font-size: 1.05rem;
        font-weight: 700;
        border-radius: 16px;
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-primary-custom {
        background-color: #3B82F6;
        border: none;
        color: white;
        box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3);
    }

    .btn-primary-custom:hover {
        background-color: #2563EB;
        transform: translateY(-5px);
        box-shadow: 0 20px 30px -10px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-secondary-custom {
        background-color: transparent;
        border: 2px solid #E2E8F0;
        color: #475569;
    }

    .btn-secondary-custom:hover {
        border-color: #3B82F6;
        color: #3B82F6;
        background-color: #F8FAFC;
        transform: translateY(-5px);
    }

    .logo-fintrack {
        display: flex;
        align-items: center;
        position: absolute;
        top: 2.5rem;
        left: 4rem;
        z-index: 10;
    }

    .logo-fintrack img {
        height: 48px;
        margin-right: 0.75rem;
    }

    .logo-fintrack h4 {
        color: #1E3A8A;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* --- Animations --- */

    /* Animasi Muncul dari Bawah dengan Blur (Elegant Reveal) */
    @keyframes revealUp {
        0% {
            opacity: 0;
            transform: translateY(40px);
            filter: blur(10px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
            filter: blur(0);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(1deg);
        }
    }

    .hero-left img {
        animation: float 6s ease-in-out infinite;
        filter: drop-shadow(0 20px 40px rgba(59, 130, 246, 0.15));
    }

    /* Mengatur delay animasi untuk teks */
    .hero-title {
        animation: revealUp 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }

    .hero-subtitle {
        animation: revealUp 1s cubic-bezier(0.2, 0.8, 0.2, 1) 0.2s forwards;
        opacity: 0;
    }

    .hero-tagline {
        animation: revealUp 1s cubic-bezier(0.2, 0.8, 0.2, 1) 0.4s forwards;
        opacity: 0;
    }

    .btn-group-custom {
        animation: revealUp 1s cubic-bezier(0.2, 0.8, 0.2, 1) 0.6s forwards;
        opacity: 0;
    }

    .logo-fintrack {
        animation: revealUp 0.8s ease-out forwards;
    }

    @media (max-width: 992px) {
        .hero-right {
            padding: 0 2rem;
            text-align: center;
            align-items: center;
        }

        .hero-title {
            font-size: 3.5rem;
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            flex-direction: column-reverse;
            padding-top: 8rem;
        }

        .logo-fintrack {
            width: 100%;
            left: 0;
            justify-content: center;
        }
    }
</style>

<div class="hero-section">
    <div class="logo-fintrack">
        <img src="{{ asset('foto/logo.png') }}" alt="FinTrack Logo">
        <h4 class="mb-0">FinTrack</h4>
    </div>

    <div class="hero-left">
        <img src="{{ asset('foto/Gambar Animasi.png') }}" alt="Ilustrasi" style="width: 100%; max-width: 600px;">
    </div>

    <div class="hero-right">
        <h1 class="hero-title">FinTrack</h1>
        <p class="hero-subtitle">Kelola Keuangan Jadi Lebih Mudah</p>
        <p class="hero-tagline">
            Hentikan kebiasaan bertanya "ke mana perginya uangku?". Catat transaksi secara otomatis, pantau anggaran real-time, dan bangun masa depan finansial yang lebih cerah mulai hari ini.
        </p>
        </p>
        <div class="d-flex flex-column flex-md-row gap-3 btn-group-custom">
            <a href="{{ route('login') }}" class="btn btn-primary-custom btn-action">Login/Daftar</a>
            <a href="{{ route('landing.page') }}" class="btn btn-secondary-custom btn-action">Pelajari Fitur</a>
        </div>
    </div>
</div>
@endsection