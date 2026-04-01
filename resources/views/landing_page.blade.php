@extends('layout.biasa')

@section('content')

<style>
    body {
        font-family: 'Inter';
        background-color: #ffffff;
    }

    /* Navbar */
    .navbar-landing {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 3rem;
        background-color: #E3FDFD;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .navbar-brand-landing {
        font-family: 'Poppins';
        font-weight: 700;
        color: #1250C3;
        font-size: 1.5rem;
    }

    .navbar-links {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .navbar-links a {
        font-family: 'Poppins';
        color: #141414;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .navbar-links a:hover {
        color: #141414;
    }

    .btn-mulai {
        font-family: 'Inter';
        background-color: #AAC4FF;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 9999px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-mulai:hover {
        background-color: #AAC4FF;
    }

    /* Hero */
    .hero-landing {
        background-color: #e6faff;
        padding: 5rem 1rem;
        text-align: left;
    }

    .hero-landing h1 {
        font-family: 'Montserrat';
        font-size: 90px;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .hero-landing h1 span {
        color: #1250C3;
    }

    .hero-landing p {
        font-family: 'Inter';
        font-size: 30px;
        color: #4b5563;
        max-width: 700px;
    }

    .btn-hero {
        font-family: 'Inter';
        padding: 0.75rem 2rem;
        border-radius: 9999px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #ffffffff;
    }

    .btn-hero:hover {
        transform: scale(1.05);
    }

    /* Section Generic */
    .section-fitur p {
        font-family: 'Inter';
        color: #8C8181;
    }

    .section-alur,
    .section-testimoni,
    .section-cta {
        padding: 4rem 0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 2rem;
    }

    .section-title h2 {
        font-family: 'Montserrat';
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .section-title p {
        font-family: 'Inter';
        color: #8C8181;
        font-size: 1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Fitur */
    .fitur-card {
        background-color: #CBF1F5;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
        font-family: 'Montserrat';
    }

    .fitur-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .icon-fitur {
        margin-bottom: 15px;
    }

    /* Alur */
    .alur-card {
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        font-family: 'Inter';
    }

    .alur-card h5 {
        font-family: 'Montserrat';
    }

    .alur-card-blue {
        background-color: #E3EBFF;
    }

    .alur-card-orange {
        background-color: #FFEDD0;
    }

    .alur-card:hover {
        transform: translateX(5px);
        background-color: #eff6ff;
    }

    /* Testimoni */
    .testimoni-card {
        font-family: 'Inter';
        background-color: #71C9CE;
        border-radius: 1rem;
        padding: 2rem;
        height: 100%;
        transition: all 0.3s ease;
        color: white;
        text-align: center;
    }

    .testimoni-card:hover {
        transform: scale(1.03);
    }

    /* CTA */
    .cta-box {
        background-color: #D2DAFF;
        border-radius: 1rem;
        padding: 3rem 2rem;
        font-family: 'Montserrat';
    }

    .cta-box p {
        font-family: 'Montserrat';
    }
</style>

{{-- Navbar --}}
<nav class="navbar-landing">
    <div class="navbar-brand-landing">FINTRACK</div>
    <div class="navbar-links">
        <a href="#fitur">Fitur</a>
        <a href="#alur">Alur</a>
        <a href="#testimoni">Testimoni</a>
        <a href="{{ route('login') }}" class="btn btn-mulai btn-hero me-2">Mulai Sekarang</a>
    </div>
</nav>

{{-- Hero Section --}}
<div class="hero-landing" data-aos="fade-up">
    <div class="container">
        <h1>Kelola Keuangan <br>
            dengan <span>FinTrack</span>, <br>
            Hidup Lebih <br>
            Tenang.</h1>
        <p>Aplikasi pelacak keuangan yang cerdas, mudah <br>
            digunakan, dan dapat diakses kapan saja untuk <br>
            membantu Anda mencapai kebebasan finansial.</p>
        <a href="{{ route('login') }}" class="btn btn-mulai btn-hero me-2">Mulai Sekarang</a>
        <a href="#alur" class="btn btn-mulai btn-hero me-2">Lihat Alur Kerja</a>
    </div>
</div>

{{-- Fitur Section --}}
<div class="section-fitur" id="fitur">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Fitur Unggulan FinTrack</h2>
            <p>Berbagai fitur yang memudahkan Anda dalam mengatur keuangan harian.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="fitur-card">
                    <i class="fa-solid fa-chart-line fa-3x m3 icon-fitur" style="color: #5862ee;"></i>
                    <h5 class="fw-bold">Laporan & Riwayat Keuangan</h5>
                    <p>Lihat semua catatan keuangan secara detail, filter berdasarkan tanggal, kategori, atau tipe transaksi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="fitur-card">
                    <i class="fa-solid fa-right-left fa-3x m3 icon-fitur" style="color: #5862ee;"></i>
                    <h5 class="fw-bold">Transfer Antar Rekening</h5>
                    <p>Pindahkan dana antar rekening pribadi dengan cepat dan mudah, lengkap dengan catatan histori.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="fitur-card">
                    <i class="fa-regular fa-bell fa-3x m3 icon-fitur" style=" color: #5862ee;"></i>
                    <h5 class="fw-bold">Pengingat Pembayaran Reguler</h5>
                    <p>Atur pengingat tagihan berulang agar tidak pernah melewatkan pembayaran penting.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="fitur-card">
                    <i class="fa-solid fa-tag fa-3x mb-3 icon-fitur" style="color: #5862ee;"></i>
                    <h5 class="fw-bold">Pengelolaan Kategori</h5>
                    <p>Atur kategori pemasukan dan pengeluaran dengan ikon dan warna khusus.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alur Section --}}
<div class="section-alur" id="alur">
    <class class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Alur Kerja FinTrack yang Sederhana</h2>
            <p>Ikuti langkah mudah untuk mulai mengelola keuangan pribadi Anda.</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-9">
                {{-- Alur 1 --}}
                <div class="alur-card alur-card-blue" data-aos="fade-right" data-aos-delay="100">
                    <span class="me-3" style="font-size: 7rem; font-weight: bold; color: #1250C3;">1</span>
                    <div>
                        <h5 class="fw-bold mb-1">Pendataan Awal Akun Keuangan</h5>
                        <p class="text-muted mb-0">Langkah pertama untuk memulai dengan FinTrack.<br><br>
                            • Daftar dan Login <br>
                            • Tambahkan Rekening (Nama, Ikon, Warna, Saldo Awal, Mata Uang)</p>
                    </div>
                </div>
                {{-- Alur 2 --}}
                <div class="alur-card alur-card-orange" data-aos="fade-left" data-aos-delay="200">
                    <div>
                        <h5 class="fw-bold mb-1">Penambahan Pemasukan & Pengeluaran</h5>
                        <p class="text-muted mb-0">Catat setiap aliran dana yang masuk dan keluar.<br><br>
                            • Pilih Tipe Transaksi: MASUK atau KELUAR<br>
                            • Isi detail: Jumlah, Tanggal, Kategori, Keterangan.</p>
                    </div>
                    <span class="ms-3" style="font-size: 7rem; font-weight: bold; color: #FF7B00;">2</span>
                </div>
                {{-- Alur 3 --}}
                <div class="alur-card alur-card-blue" data-aos="fade-right" data-aos-delay="300">
                    <span class="me-3" style="font-size: 7rem; font-weight: bold; color: #1250C3;">3</span>
                    <div>
                        <h5 class="fw-bold mb-1">Transfer Antar Rekening</h5>
                        <p class="text-muted mb-0">Pindahkan dana antar rekening pribadi Anda dengan aman.<br><br>
                            • Pilih rekening asal dan tujuan<br>
                            • Masukkan jumlah dan tanggal transfer</p>
                    </div>
                </div>
                {{-- Alur 4 --}}
                <div class="alur-card alur-card-orange" data-aos="fade-left" data-aos-delay="400">
                    <div>
                        <h5 class="fw-bold mb-1">Kelola Kategori & Pengingat</h5>
                        <p class="text-muted mb-0">Personalisasi pencatatan keuangan Anda.<br><br>
                            • Tambah, edit, dan hapus kategori keuangan<br>
                            • Buat pengingat untuk pembayaran rutin
                    </div>
                    <span class="ms-3" style="font-size: 7rem; font-weight: bold; color: #FF7B00;">4</span>
                </div>
                {{-- Alur 5 --}}
                <div class="alur-card alur-card-blue" data-aos="fade-right" data-aos-delay="500">
                    <span class="me-3" style="font-size: 7rem; font-weight: bold; color: #1250C3;">5</span>
                    <div>
                        <h5 class="fw-bold mb-1">Lihat Laporan & Riwayat</h5>
                        <p class="text-muted mb-0">Dapatkan gambaran jelas tentang kondisi keuangan Anda.<br><br>
                            • Tinjau riwayat transaksi lengkap<br>
                            • Gunakan filter untuk analisis yang lebih spesifik</p>
                    </div>
                </div>
            </div>
        </div>
    </class>
</div>
{{-- Testimoni Section --}}
<div class="section-testimoni" id="testimoni">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Apa Kata Mereka Tentang FinTrack?</h2>
            <p>Pendapat pengguna yang sudah merasakan manfaat FinTrack.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="testimoni-card">
                    <p>"FinTrack membuat saya lebih sadar akan pengeluaran harian. Tampilannya sederhana dan sangat intuitif!"</p>
                    <small>- Naiga, Wirausaha</small>
                </div>
            </div>
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="testimoni-card">
                    <p>"Fitur pengingatnya adalah penyelamat! Saya tidak pernah lagi telat membayar tagihan penting."</p>
                    <small>- Tiara, Siswa Sekolah</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CTA Section --}}
<div class="section-cta">
    <div class="container">
        <div class="cta-box text-center" data-aos="fade-up">
            <h2 class="fw-bold mb-3">Siap Mengatur Keuanganmu</h2>
            <p class="text-muted mb-4">Gabung sekarang dan rasakan kemudahan mengelola keuangan Anda.</p>
            <a href="{{ route('login') }}" class="btn btn-mulai btn-hero me-2 btn-lg rounded-pill">Mulai Sekarang</a>
        </div>
    </div>
</div>

{{-- Footer --}}
<footer class="bg-light text-center py-3 mt-5">
    <div class="container">
        <p class="mb-0 text-muted">
            &copy; 2024 FinTrack. Semua hak dilindungi.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
    });
</script>
@endsection