@extends('layout.app')

@section('content')
{{-- Logika Terjemahan Sederhana untuk Halaman Ini --}}
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
'security_title' => $isEn ? 'Security' : 'Keamanan',
'pin_title' => $isEn ? 'App PIN Lock' : 'Kunci PIN Aplikasi',
'pin_sub' => $isEn ? 'Use 6 digits number' : 'Gunakan 6 digit angka',
'modal_profile' => $isEn ? 'Update Profile' : 'Ubah Profil',
'modal_name' => $isEn ? 'Full Name' : 'Nama Lengkap',
'modal_photo' => $isEn ? 'Profile Photo' : 'Foto Profil',
'modal_save' => $isEn ? 'Save Changes' : 'Simpan Perubahan',
'modal_cancel' => $isEn ? 'Cancel' : 'Batal',
'modal_pin_head' => $isEn ? 'Set Security PIN' : 'Setel PIN Keamanan',
];
@endphp

<style>
    /* CSS Modal Overlay agar muncul di tengah */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px);
    }

    .modal-overlay:target {
        display: flex;
    }

    .card-custom {
        transition: transform 0.2s;
        border: none;
    }

    .card-custom:hover {
        transform: translateY(-5px);
    }

    /* Mencegah input PIN panah atas bawah */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<div class="main-content">
    {{-- HEADER & DARK MODE TOGGLE --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">{{ $text['title'] }}</h2>
        <button onclick="toggleTheme()" class="btn btn-white rounded-pill shadow-sm border-0 px-4 py-2" style="background: white; color: #333;">
            <i class="bi bi-moon-stars-fill me-2" id="theme-icon"></i>
            <span id="theme-text">{{ $text['dark_mode'] }}</span>
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row g-4">
        {{-- CARD PROFIL --}}
        <div class="col-md-7">
            <div class="card-custom p-4 shadow-sm h-100" style="background-color: #d8e9ef; border-radius: 20px;">
                <h6 class="fw-bold text-dark mb-4">{{ $text['profile_title'] }}</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ $user->foto_profil ? asset('images/profil/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.$user->name }}"
                            class="rounded-4 shadow-sm" style="width:70px; height:70px; object-fit:cover; border: 3px solid white;">
                        <div class="ms-3">
                            <h5 class="fw-bold mb-0 text-dark">{{ $user->name }}</h5>
                            <p class="text-muted small mb-0">ID: #{{ $user->id }}</p>
                        </div>
                    </div>
                    <a href="#modalProfil" class="btn btn-dark btn-sm rounded-pill px-4 py-2 shadow-sm">{{ $text['edit_btn'] }}</a>
                </div>
            </div>
        </div>

        {{-- CARD BAHASA --}}
        <div class="col-md-5">
            <div class="card-custom p-4 shadow-sm h-100" style="background-color: #eee3d7; border-radius: 20px;">
                <h6 class="fw-bold text-dark mb-3">{{ $text['lang_title'] }}</h6>
                <form action="{{ route('pengaturan.preferensi') }}" method="POST">
                    @csrf
                    <select name="bahasa" class="form-select rounded-pill border-0 mb-3 shadow-sm py-2 px-3">
                        <option value="Indonesia" {{ $pengaturan->bahasa == 'Indonesia' ? 'selected' : '' }}>🇮🇩 Bahasa Indonesia</option>
                        <option value="English" {{ $pengaturan->bahasa == 'English' ? 'selected' : '' }}>🇺🇸 English</option>
                    </select>
                    <button type="submit" class="btn btn-dark btn-sm w-100 rounded-pill py-2 shadow-sm">{{ $text['lang_btn'] }}</button>
                </form>
            </div>
        </div>

        {{-- CARD KEAMANAN (PIN) --}}
        <div class="col-md-6">
            <div class="card-custom p-4 shadow-sm" style="background-color: #f5f1e9; border-radius: 20px;">
                <h6 class="fw-bold text-dark mb-3">{{ $text['security_title'] }}</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-white p-2 rounded-3 me-3 shadow-sm">
                            <i class="bi bi-shield-lock-fill text-warning fs-4"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold text-dark">{{ $text['pin_title'] }}</p>
                            <small class="text-muted">{{ $text['pin_sub'] }}</small>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" style="width: 2.5em; height: 1.25em; cursor: pointer;"
                            onchange="if(this.checked) window.location.href='#modalPin'">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PIN --}}
<div id="modalPin" class="modal-overlay">
    <div class="bg-white p-4 shadow-lg text-dark" style="width: 380px; border-radius: 25px;">
        <h5 class="fw-bold text-center mb-4">{{ $text['modal_pin_head'] }}</h5>
        <form action="{{ route('pengaturan.pin') }}" method="POST">
            @csrf
            <div class="mb-4 text-center">
                {{-- Type number & pattern agar hanya 6 digit angka --}}
                <input type="number" name="pin"
                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                    maxlength="6"
                    class="form-control text-center fs-2 rounded-4 fw-bold border-2"
                    placeholder="••••••" required style="letter-spacing: 8px;">
                <small class="text-muted mt-2 d-block">{{ $text['pin_sub'] }}</small>
            </div>
            <div class="d-flex justify-content-center gap-2">
                <a href="#" class="btn btn-light rounded-pill px-4">{{ $text['modal_cancel'] }}</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Save PIN</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL PROFIL --}}
<div id="modalProfil" class="modal-overlay">
    <div class="p-4 text-dark" style="background: #e1f2e5; width: 450px; border-radius: 25px;">
        <h5 class="fw-bold mb-4"><i class="bi bi-person-circle me-2"></i>{{ $text['modal_profile'] }}</h5>
        <form action="{{ route('pengaturan.profil') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 text-center">
                <img id="previewImg" src="{{ $user->foto_profil ? asset('images/profil/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.$user->name }}"
                    class="rounded-pill mb-3 border border-3 border-white shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
            </div>
            <div class="mb-3">
                <label class="small fw-bold">{{ $text['modal_name'] }}</label>
                <input type="text" name="nama" value="{{ $user->name }}" class="form-control rounded-pill border-0 shadow-sm px-3" required>
            </div>
            <div class="mb-4">
                <label class="small fw-bold">{{ $text['modal_photo'] }}</label>
                <input type="file" name="foto" class="form-control rounded-pill border-0 shadow-sm" onchange="previewFile(this)">
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="#" class="btn btn-light rounded-pill px-4">{{ $text['modal_cancel'] }}</a>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">{{ $text['modal_save'] }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview Gambar saat Edit Profil
    function previewFile(input) {
        var file = $("input[type=file]").get(0).files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function() {
                $("#previewImg").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    }

    // Fungsi Mode Gelap/Terang
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const targetTheme = currentTheme === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', targetTheme);
        localStorage.setItem('theme', targetTheme);
        updateThemeUI(targetTheme);
    }

    function updateThemeUI(theme) {
        const icon = document.getElementById('theme-icon');
        const text = document.getElementById('theme-text');
        const isEn = "{{ session('locale') == 'en' }}";

        if (theme === 'dark') {
            icon.className = 'bi bi-sun-fill me-2';
            text.innerText = isEn ? 'Light Mode' : 'Mode Terang';
        } else {
            icon.className = 'bi bi-moon-stars-fill me-2';
            text.innerText = isEn ? 'Dark Mode' : 'Mode Gelap';
        }
    }

    // Jalankan saat halaman dibuka untuk sinkronisasi tombol
    document.addEventListener('DOMContentLoaded', function() {
        updateThemeUI(localStorage.getItem('theme') || 'light');
    });
</script>
@endsection