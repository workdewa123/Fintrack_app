<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinTrack</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Tambahkan script Iconify --}}
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <script src="   https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <style>
        /* Definisi font QuickShunt untuk judul */
        @font-face {
            font-family: 'QuickShunt';
            src: url('/fonts/QuickShunt-Bold.woff2') format('woff2'),
                url('/fonts/QuickShunt-Bold.woff') format('woff');
            font-weight: bold;
            font-style: normal;
        }

        /* Definisi font Mantrop untuk navigasi */
        @font-face {
            font-family: 'Mantrop';
            src: url('/fonts/Mantrop-Medium.woff2') format('woff2'),
                url('/fonts/Mantrop-Medium.woff') format('woff');
            font-weight: 500;
            font-style: normal;
        }

        body {
            background-color: #f4f9ff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .logo-fintrack {
            position: relative;
            top: unset;
            left: unset;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .sidebar {
            width: 240px;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
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
            color: #4b5563;
            font-weight: 500;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 10px;
            margin: 4px 12px;
            transition: all 0.2s;
            font-family: 'Mantrop', sans-serif;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
        }

        .topbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #fcd34d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .top-icon {
            font-size: 20px;
            color: #6c757d;
            cursor: pointer;
            position: relative;
        }

        .notification-dot {
            position: absolute;
            top: 2px;
            right: 0px;
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 1px solid #f4f9ff;
        }

        .card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .card.total-rekening {
            background-color: #3b82f6;
            color: white;
        }

        .card.total-pemasukan {
            background-color: #e8f9ef;
            color: #333;
        }

        .card.total-pengeluaran {
            background-color: #fdeaea;
            color: #333;
        }

        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            border-radius: 12px;
        }

        .btn-light {
            color: #3b82f6;
            border-color: #3b82f6;
            border-radius: 8px;
        }

        .btn-light:hover {
            background-color: #e0e7ff;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="brand">
            {{-- Menggunakan logo dari file lokal --}}
            <img src="{{ asset('foto/logo.png') }}" alt="FinTrack Logo" style="width: 24px; height: 24px;"> Fintrack
        </div>
        <nav class="nav flex-column">
            <a href="{{ url('/beranda') }}" class="nav-link {{ request()->is('beranda') ? 'active' : '' }}">
                {{-- Menggunakan ikon Iconify untuk Beranda --}}
                <iconify-icon icon="tabler:home"></iconify-icon> Beranda
            </a>
            <a href="{{ url('/rekening') }}" class="nav-link {{ request()->is('rekening') ? 'active' : '' }}">
                {{-- Menggunakan ikon Iconify untuk Kelola Rekening --}}
                <iconify-icon icon="icon-park-outline:transaction-order"></iconify-icon> Kelola Rekening
            </a>
            <a href="{{ url('/kategori') }}" class="nav-link {{ request()->is('kategori') ? 'active' : '' }}">
                {{-- Menggunakan ikon Iconify untuk Kategori Keuangan --}}
                <iconify-icon icon="tabler:category-filled"></iconify-icon> Kategori Keuangan
            </a>
            <a href="{{ url('/pembayaran-reguler') }}" class="nav-link {{ request()->is('pembayaran-reguler') ? 'active' : '' }}">
                {{-- Menggunakan ikon Iconify untuk Pembayaran Reguler --}}
                <iconify-icon icon="hugeicons:transaction"></iconify-icon> Pembayaran Reguler
            </a>
            <a href="{{ url('/pengingat') }}" class="nav-link {{ request()->is('pengingat') ? 'active' : '' }}">
                {{-- Menggunakan ikon Iconify untuk Pengingat --}}
                <iconify-icon icon="solar:bell-bold"></iconify-icon> Pengingat
            </a>
            <a href="{{ url('/pengaturan') }}" class="nav-link {{ request()->is('pengaturan') ? 'active' : '' }}">
                {{-- Menggunakan ikon Iconify untuk Pengaturan --}}
                <iconify-icon icon="solar:settings-bold"></iconify-icon> Pengaturan
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="top-icon-container" style="position: relative;">
                {{-- Menggunakan ikon Iconify untuk notifikasi di topbar --}}
                <iconify-icon icon="solar:bell-bold"></iconify-icon>
                <span class="notification-dot"></span>
            </div>

            <div class="user-info">
                <div class="user-avatar"></div>
                <div>
                    <div class="fw-bold">Tiara Syifa</div>
                    <small class="text-muted">Akun User</small>
                </div>
            </div>
        </div>

        <main>
            @yield('content')
        </main>
    </div>

    {{-- Script yang sudah diperbaiki --}}
    <s src="https://code.jquery.com/jquery-3.6.0.min.js"></s>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')
</body>

</html>