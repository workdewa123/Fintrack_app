<!DOCTYPE html>
{{-- Menggunakan session locale untuk tag lang --}}
<html lang="{{ session('locale') == 'en' ? 'en' : 'id' }}" data-theme="light">

<head>
    <script>
        // Cek tema sebelum halaman dirender agar tidak putih sekejap
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinTrack</title>

    {{-- CSS Frameworks --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Script Iconify --}}
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <style>
        /* CSS VARIABEL UNTUK DARK MODE */
        :root {
            --bg-body: #f4f9ff;
            --sidebar-bg: #ffffff;
            --text-main: #4b5563;
            --card-default: #ffffff;
        }

        [data-theme="dark"] {
            --bg-body: #121212;
            --sidebar-bg: #1a1a1a;
            --text-main: #e5e7eb;
            --card-default: #2d2d2d;
        }

        /* Definisi font QuickShunt & Mantrop */
        @font-face {
            font-family: 'QuickShunt';
            src: url('/fonts/QuickShunt-Bold.woff2') format('woff2');
            font-weight: bold;
        }

        @font-face {
            font-family: 'Mantrop';
            src: url('/fonts/Mantrop-Medium.woff2') format('woff2');
            font-weight: 500;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            transition: 0.3s;
        }

        .sidebar {
            width: 240px;
            background: var(--sidebar-bg);
            border-right: 1px solid #e5e7eb;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            transition: 0.3s;
            z-index: 1000;
        }

        [data-theme="dark"] .sidebar {
            border-right: 1px solid #333;
        }

        .sidebar .brand {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #0F4264;
            font-family: 'QuickShunt', sans-serif;
        }

        .sidebar .nav-link {
            color: var(--text-main);
            font-weight: 500;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 10px;
            margin: 4px 12px;
            transition: all 0.2s;
            font-family: 'Mantrop', sans-serif;
            text-decoration: none;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .user-avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #3b82f6;
        }

        .notification-dot {
            position: absolute;
            top: 2px;
            right: 0px;
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 1px solid var(--bg-body);
        }

        .card {
            background-color: var(--card-default);
            color: var(--text-main);
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    {{-- SIDEBAR --}}
    <div class="sidebar">
        <div class="brand">
            <img src="{{ asset('foto/logo.png') }}" alt="Logo" style="width: 24px; height: 24px;"> Fintrack
        </div>
        <nav class="nav flex-column">
            {{-- Beranda --}}
            <a href="{{ url('/beranda') }}" class="nav-link {{ request()->is('beranda') ? 'active' : '' }}">
                <iconify-icon icon="tabler:home"></iconify-icon>
                {{ session('locale') == 'en' ? 'Dashboard' : 'Beranda' }}
            </a>

            {{-- Kelola Rekening --}}
            <a href="{{ url('/rekening') }}" class="nav-link {{ request()->is('rekening') ? 'active' : '' }}">
                <iconify-icon icon="icon-park-outline:transaction-order"></iconify-icon>
                {{ session('locale') == 'en' ? 'Manage Account' : 'Kelola Rekening' }}
            </a>

            {{-- Kategori --}}
            <a href="{{ url('/kategori') }}" class="nav-link {{ request()->is('kategori') ? 'active' : '' }}">
                <iconify-icon icon="tabler:category-filled"></iconify-icon>
                {{ session('locale') == 'en' ? 'Category' : 'Kategori Keuangan' }}
            </a>

            {{-- PEMBAYARAN REGULER (PENGINGAT SUDAH JADI SATU DI SINI) --}}
            {{-- Menggunakan wildcard '*' agar tetap active di semua sub-halaman pembayaran --}}
            <a href="{{ route('pembayaran.index') }}" class="nav-link {{ request()->is('pembayaran-reguler*') ? 'active' : '' }}">
                <iconify-icon icon="hugeicons:transaction"></iconify-icon>
                {{ session('locale') == 'en' ? 'Regular Payment' : 'Pembayaran Reguler' }}
            </a>

            {{-- Pengaturan --}}
            <a href="{{ url('/pengaturan') }}" class="nav-link {{ request()->is('pengaturan') ? 'active' : '' }}">
                <iconify-icon icon="solar:settings-bold"></iconify-icon>
                {{ session('locale') == 'en' ? 'Settings' : 'Pengaturan' }}
            </a>

            {{-- Tombol Logout (Tambahan agar fungsional) --}}
            <form action="{{ route('logout') }}" method="POST" class="mt-4 px-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        <div class="topbar">
            {{-- Notifikasi --}}
            <div class="top-icon-container" style="position: relative; font-size: 20px; cursor: pointer;">
                <iconify-icon icon="solar:bell-bold"></iconify-icon>
                <span class="notification-dot"></span>
            </div>

            {{-- User Profil --}}
            <div class="user-info d-flex align-items-center gap-2">
                @if(Auth::check())
                <img src="{{ Auth::user()->foto_profil ? asset('images/profil/'.Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name='.Auth::user()->name }}" class="user-avatar-img">
                <div class="d-none d-sm-block">
                    <div class="fw-bold" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                    <small class="text-muted" style="font-size: 0.75rem;">{{ session('locale') == 'en' ? 'User Account' : 'Akun User' }}</small>
                </div>
                @endif
            </div>
        </div>

        <main>
            {{-- Pesan Alert Global (Sukses/Error) --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- Script JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>