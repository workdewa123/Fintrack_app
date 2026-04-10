@extends('layout.biasa')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-dark: #1E3A8A;
        --primary-blue: #3E7AD8;
        /* Warna biru sedikit lebih cerah sesuai gambar */
        --secondary-blue: #2381DD;
        --text-slate: #62738B;
        --deep-blue: #0B3863;
        --soft-bg: #F0F7FF;
        /* Warna background lembut agar tidak full putih */
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #ffffff;
        /* Dasar tetap putih */
    }

    /* Navbar - Diberi sedikit warna transparan agar menarik */
    .navbar-landing {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 3rem;
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.05);
        border-bottom: 1px solid rgba(59, 130, 246, 0.1);
    }

    .navbar-brand-landing {
        font-family: 'Montserrat';
        font-weight: 800;
        color: var(--primary-dark);
        /* Menggunakan warna 1E3A8A */
        font-size: 1.5rem;
        letter-spacing: -1px;
    }

    .navbar-links {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .navbar-links a {
        color: var(--text-slate);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .navbar-links a:hover {
        color: var(--primary-blue);
    }

    /* Perbaikan: Teks Putih di Navbar */
    .btn-mulai {
        background-color: var(--primary-blue);
        color: #ffffff !important;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .btn-mulai:hover {
        background-color: var(--secondary-blue);
        color: #ffffff !important;
        transform: translateY(-2px);
    }

    /* Hero Section - Diberi background gradasi lembut */
    .hero-landing {
        background: linear-gradient(135deg, #E3FDFD 0%, var(--soft-bg) 100%);
        padding: 8rem 1rem;
        text-align: left;
        position: relative;
        overflow: hidden;
    }

    /* Aksen Dekoratif ditingkatkan warnanya */
    .hero-landing::after {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        top: -100px;
        right: -100px;
    }

    .hero-landing h1 {
        font-family: 'Montserrat';
        font-size: 75px;
        font-weight: 800;
        margin-bottom: 1.5rem;
        line-height: 1.1;
        letter-spacing: -2px;
        color: var(--deep-blue);
    }

    .hero-landing h1 span {
        color: var(--primary-blue);
    }

    .hero-landing p {
        font-size: 1.25rem;
        color: var(--text-slate);
        max-width: 750px;
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }

    .btn-hero {
        padding: 1rem 2.5rem;
        border-radius: 15px;
        font-weight: 700;
        display: inline-block;
    }

    .btn-secondary-custom {
        background-color: #ffffff;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
        transition: all 0.3s ease;
    }

    .btn-secondary-custom:hover {
        background-color: var(--primary-blue);
        color: white;
    }

    /* Fitur Section */
    .section-fitur {
        background-color: #F8FAFC;
    }

    .section-fitur p {
        color: var(--text-slate);
    }

    .section-alur,
    .section-testimoni,
    .section-cta {
        padding: 6rem 0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 4rem;
    }

    .section-title h2 {
        font-family: 'Montserrat';
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 1rem;
    }

    .section-title p {
        color: var(--text-slate);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Fitur Card */
    .fitur-card {
        background-color: #F0F7FF;
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        height: 100%;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(59, 130, 246, 0.1);
    }

    .fitur-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 30px rgba(30, 58, 138, 0.1);
        border-color: var(--primary-blue);
    }

    .icon-fitur {
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }

    .fitur-card:hover .icon-fitur {
        transform: scale(1.1);
    }

    /* Alur Card */
    .alur-card {
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        background-color: #ffffff;
    }

    .alur-card h5 {
        font-family: 'Montserrat';
        font-weight: 700;
        color: var(--primary-dark);
    }

    .alur-card-blue {
        background-color: var(--soft-bg);
    }

    .alur-card-orange {
        background-color: #FFF7ED;
    }

    .alur-card:hover {
        transform: translateX(10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    /* Testimoni */
    .testimoni-card {
        background: #8FC2F8;
        border-radius: 20px;
        padding: 2.5rem;
        height: 100%;
        transition: all 0.3s ease;
        color: white;
        text-align: center;
        box-shadow: 0 10px 20px rgba(35, 129, 221, 0.2);
    }

    .testimoni-card p {
        font-style: italic;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .testimoni-card:hover {
        transform: scale(1.02);
    }

    /* CTA Box */
    .cta-box {
        background: var(--primary-blue);
        border-radius: 25px;
        padding: 5rem 2rem;
        color: white;
    }

    .cta-box h2 {
        font-family: 'Montserrat';
        font-weight: 800;
    }

    /* Perbaikan: Tombol CTA Putih teks Biru */
    .btn-cta-white {
        background-color: #ffffff !important;
        color: var(--primary-blue) !important;
        border: none;
        padding: 1rem 3rem;
        font-weight: 700;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-cta-white:hover {
        background-color: #f8f9fa !important;
        transform: scale(1.05);
    }

    /* Footer */
    footer {
        background-color: #F8FAFC;
    }
</style>

{{-- Navbar --}}
<nav class="navbar-landing">
    <div class="navbar-brand-landing">FINTRACK</div>
    <div class="navbar-links">
        <a href="#fitur">Keunggulan</a>
        <a href="#alur">Alur</a>
        <a href="#testimoni">Testimoni</a>
        <a href="{{ route('login') }}" class="btn-mulai">Coba Sekarang</a>
    </div>
</nav>

{{-- Hero Section --}}
<div class="hero-landing" data-aos="fade-up">
    <div class="container">
        <h1>Bebas Finansial <br>
            Bersama <span>FinTrack</span>, <br>
            Masa Depan <br>
            Lebih Cerah.</h1>
        <p>Solusi cerdas manajemen keuangan pribadi yang transparan. Pantau setiap rupiah, atur tagihan rutin, dan buat perencanaan masa depan dalam satu platform yang elegan.</p>
        <a href="{{ route('login') }}" class="btn btn-mulai btn-hero me-3">Mulai Perjalananmu</a>
        <a href="#alur" class="btn btn-secondary-custom btn-hero shadow-sm">Lihat Alur Kerja</a>
    </div>
</div>

{{-- Fitur Section --}}
<div class="section-fitur min-vh-100 d-flex align-items-center" id="fitur">
    <div class="container py-5">
        <div class="section-title" data-aos="fade-up">
            <h2 class="fw-bold">Kenapa Memilih FinTrack?</h2>
            <p>Fitur lengkap yang dirancang khusus untuk memudahkan pengelolaan finansial harian Anda secara efektif.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="fitur-card shadow-sm">
                    <i class="fa-solid fa-receipt fa-3x mb-3 icon-fitur" style="color: var(--primary-blue);"></i>
                    <h5 class="fw-bold">Riwayat Transaksi Akurat</h5>
                    <p>Catat dan tinjau seluruh riwayat transaksi Anda kapan saja. Gunakan filter canggih untuk menemukan data pengeluaran tertentu dalam hitungan detik.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="fitur-card shadow-sm">
                    <i class="fa-solid fa-clock-rotate-left fa-3x mb-3 icon-fitur" style="color: var(--primary-blue);"></i>
                    <h5 class="fw-bold">Pembayaran Reguler</h5>
                    <p>Jangan pernah telat membayar tagihan rutin lagi. Atur jadwal pembayaran listrik, wifi, atau cicilan dengan sistem pengingat otomatis.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="fitur-card shadow-sm">
                    <i class="fa-solid fa-user-gear fa-3x mb-3 icon-fitur" style="color: var(--primary-blue);"></i>
                    <h5 class="fw-bold">Pengaturan Personal</h5>
                    <p>Personalisasi profil Anda, ubah preferensi akun, dan kendalikan keamanan data Anda melalui menu pengaturan yang intuitif.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alur Section --}}
<div class="section-alur" id="alur">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Langkah Mudah Menggunakan FinTrack</h2>
            <p>Proses integrasi keuangan yang sederhana agar Anda bisa fokus pada tujuan finansial Anda.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="alur-card alur-card-blue" data-aos="fade-right" data-aos-delay="100">
                    <span class="me-4" style="font-size: 6rem; font-weight: 800; color: var(--primary-blue); opacity: 0.3;">01</span>
                    <div>
                        <h5 class="fw-bold mb-2">Setup Akun & Rekening</h5>
                        <p class="text-muted mb-0">Daftarkan akun Anda dan mulai tambahkan berbagai sumber dana (Bank, E-Wallet, atau Tunai) lengkap dengan saldo awal dan ikon unik.</p>
                    </div>
                </div>
                <div class="alur-card alur-card-orange" data-aos="fade-left" data-aos-delay="200">
                    <div>
                        <h5 class="fw-bold mb-2">Kelola Transaksi & Pembayaran</h5>
                        <p class="text-muted mb-0">Catat pemasukan atau pengeluaran harian, serta jadwalkan pembayaran reguler Anda agar aliran kas tetap terkontrol dengan baik.</p>
                    </div>
                    <span class="ms-4" style="font-size: 6rem; font-weight: 800; color: #F97316; opacity: 0.3;">02</span>
                </div>
                <div class="alur-card alur-card-blue" data-aos="fade-right" data-aos-delay="300">
                    <span class="me-4" style="font-size: 6rem; font-weight: 800; color: var(--primary-blue); opacity: 0.3;">03</span>
                    <div>
                        <h5 class="fw-bold mb-2">Monitor Riwayat & Evaluasi</h5>
                        <p class="text-muted mb-0">Tinjau laporan berkala di menu Riwayat Transaksi untuk melihat pola keuangan Anda dan lakukan penyesuaian di menu Pengaturan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Testimoni Section --}}
<div class="section-testimoni" id="testimoni">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Membantu Ribuan Orang Seperti Anda</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="testimoni-card">
                    <p>"Berkat menu Riwayat Transaksi, saya jadi tahu kalau pengeluaran kopi saya sangat besar! Sekarang keuangan saya jauh lebih sehat."</p>
                    <small class="fw-bold">- Naiga, Wirausaha Muda</small>
                </div>
            </div>
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="testimoni-card" ;>
                    <p>"Fitur Pembayaran Reguler benar-benar penyelamat. Saya tidak pernah lupa bayar internet kantor lagi. Sangat direkomendasikan!"</p>
                    <small class="fw-bold">- Tiara, Pelajar & Freelancer</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CTA Section --}}
<div class="section-cta">
    <div class="container">
        <div class="cta-box text-center" data-aos="fade-up">
            <h2 class="fw-bold mb-3">Mulai Kendalikan Keuangan Anda Hari Ini</h2>
            <p class="text-white mb-4">Bergabunglah dengan komunitas FinTrack dan rasakan ketenangan dalam mengelola dana Anda</p>
            {{-- Tombol CTA yang sudah direvisi --}}
            <a href="{{ route('login') }}" class="btn-cta-white btn-hero btn-lg rounded-pill px-5">Mulai Sekarang — Gratis</a>
        </div>
    </div>
</div>

{{-- Footer --}}
<footer class="text-center py-5 border-top">
    <div class="container">
        <div class="navbar-brand-landing mb-3">FINTRACK</div>
        <p class="mb-0 text-muted small">
            &copy; 2026 FinTrack. Didesain untuk kemudahan manajemen keuangan Anda.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true,
    });
</script>
@endsection