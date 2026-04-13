@extends('layout.app')

@section('content')
@php
    $isEn = session('locale') == 'en';
    $text = [
        'title' => $isEn ? 'Settings' : 'Pengaturan',
        'dark_mode' => $isEn ? 'Dark Mode' : 'Mode Gelap',
        'light_mode' => $isEn ? 'Light Mode' : 'Mode Terang',
        'profile_title' => $isEn ? 'User Profile' : 'Profil Pengguna',
        'edit_btn' => $isEn ? 'Edit Profile' : 'Edit Profil',
        'lang_title' => $isEn ? 'Language' : 'Bahasa',
        'lang_btn' => $isEn ? 'Apply Language' : 'Terapkan Bahasa',
    ];
@endphp

<style>
    .card-settings {
        border: none;
        border-radius: 16px;
        background-color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    /* Warna biru utama agar konsisten dengan kartu "Total Keseluruhan" */
    .bg-fintrack-blue {
        background-color: #4a90e2 !important;
        color: white;
    }

    /* Warna krem lembut agar konsisten dengan kartu "Total Pengeluaran" */
    .bg-fintrack-soft-red {
        background-color: #fff1f1 !important;
    }

    .btn-fintrack {
        background-color: #4e92ff;
        color: white;
        border-radius: 10px;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-fintrack:hover {
        background-color: #4a90e2;
        color: white;
    }
</style>

<div class="container-fluid p-0">
    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">{{ $text['title'] }}</h2>
        <p class="text-muted small">Kelola dan pantau preferensi akun Anda</p>
    </div>

    <div class="row g-4">

        {{-- DARK MODE TOGGLE (Baris Baru) --}}
        <div class="col-12">
            <div class="card-settings p-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 rounded-3 bg-light text-primary">
                        <iconify-icon icon="solar:moon-bold" class="fs-4"></iconify-icon>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Tampilan Aplikasi</h6>
                        <small class="text-muted">Ganti tema antara mode terang dan gelap</small>
                    </div>
                </div>
                <button onclick="toggleTheme()" class="btn btn-outline-primary rounded-pill px-4">
                    <span id="theme-text">{{ $text['dark_mode'] }}</span>
                </button>
            </div>
        </div>

        {{-- CARD PROFIL - Menggunakan tema Biru Utama --}}
        <div class="col-md-7">
            <div class="card-settings p-4 h-100 bg-fintrack-blue">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h6 class="fw-bold mb-0 opacity-90">{{ $text['profile_title'] }}</h6>
                    <iconify-icon icon="solar:user-circle-bold" class="fs-3"></iconify-icon>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ Auth::user()->foto_profil ? asset('images/profil/'.Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama) }}"
                            class="rounded-circle shadow-sm" style="width:70px; height:70px; object-fit:cover; border: 3px solid rgba(255,255,255,0.4);">
                        <div class="ms-3">
                            <h5 class="fw-bold mb-0">{{ Auth::user()->nama }}</h5>
                            <p class="small mb-0 opacity-80">@<span>{{ Auth::user()->username }}</span></p>
                        </div>
                    </div>
                    <button data-bs-toggle="modal" data-bs-target="#modalEditProfil" class="btn btn-light rounded-pill px-4 shadow-sm fw-bold" style="color: #4e92ff;">
                        {{ $text['edit_btn'] }}
                    </button>
                </div>
            </div>
        </div>

        {{-- CARD BAHASA - Menggunakan tema Soft (seperti kartu pengeluaran) --}}
        <div class="col-md-5">
            <div class="card-settings p-4 h-100 bg-fintrack-soft-red">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h6 class="fw-bold mb-0 text-dark">{{ $text['lang_title'] }}</h6>
                    <iconify-icon icon="solar:global-bold" class="fs-3 text-danger opacity-50"></iconify-icon>
                </div>

                <form action="{{ route('pengaturan.preferensi') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="bahasa" class="form-select border-0 shadow-sm rounded-3 py-2">
                            <option value="Indonesia" {{ (isset($pengaturan) && $pengaturan->bahasa == 'Indonesia') ? 'selected' : '' }}>🇮🇩 Bahasa Indonesia</option>
                            <option value="English" {{ (isset($pengaturan) && $pengaturan->bahasa == 'English') ? 'selected' : '' }}>🇺🇸 English</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-fintrack w-100 py-2 shadow-sm">
                        {{ $text['lang_btn'] }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateThemeUI(theme) {
        const text = document.getElementById('theme-text');
        const isEn = "{{ session('locale') == 'en' ? 'true' : 'false' }}" === "true";
        if(text) text.innerText = theme === 'dark' ? (isEn ? 'Light Mode' : 'Mode Terang') : (isEn ? 'Dark Mode' : 'Mode Gelap');
    }
</script>
@endsection
