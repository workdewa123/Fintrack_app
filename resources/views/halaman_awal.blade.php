@extends('layout.biasa')
@section('content')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #ffffff;
        margin: 0;
        padding: 0;
    }

    .hero-section {
        display: flex;
        align-items: center;
        min-height: 100vh;
        padding: 2rem;
        background-color: #F0F4F8;
        flex-wrap: wrap;
        overflow: hidden;
    }

    .hero-left {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
    }

    .hero-right {
        flex: 1;
        padding: 0 4rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .hero-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 3rem;
        font-weight: 700;
        color: #2D4373;
        margin-bottom: 1rem;
    }

    .hero-subtitle {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.25rem;
        color: #2D4373;
        margin-bottom: 0.5rem;
        font-weight: 400;
    }

    .hero-tagline {
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        color: #62738B;
        font-style: italic;
        margin-bottom: 2rem;
    }

    .btn-action {
        width: 250px;
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 9999px;
        transition: transform 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary-custom {
        background-color: #3B82F6;
        border-color: #3B82F6;
        color: white;
    }

    .btn-primary-custom:hover {
        background-color: #2563EB;
        border-color: #2563EB;
        color: white;
    }

    .btn-secondary-custom {
        background-color: white;
        border: 2px solid #3B82F6;
        color: #3B82F6;
    }

    .btn-secondary-custom:hover {
        background-color: #F0F4F8;
        border-color: #2563EB;
        color: #2563EB;
    }

    .logo-fintrack {
        display: flex;
        align-items: center;
        position: absolute;
        top: 2rem;
        left: 2rem;
        z-index: 10;
        opacity: 0;
    }

    .logo-fintrack img {
        height: 40px;
        margin-right: 0.5rem;
    }

    /* --- Media Queries --- */
    @media (max-width: 768px) {
        .hero-section {
            flex-direction: column;
            text-align: center;
        }

        .hero-right {
            align-items: center;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .logo-fintrack {
            position: relative;
            top: unset;
            left: unset;
            justify-content: center;
            margin-bottom: 2rem;
        }
    }

    /* --- CSS Animations --- */

    /* Keyframes for Fade-in & Slide-up Effects */
    @keyframes fadeInSlideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Keyframes for Floating Effect on the Image */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-10px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    /* Applying Animations to the Elements */
    .hero-left img {
        animation: float 3s ease-in-out infinite;
    }

    .logo-fintrack {
        animation: fadeInSlideUp 1s ease-out forwards;
        animation-delay: 0.2s;
        opacity: 0;
    }

    .hero-left {
        animation: fadeInSlideUp 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        animation-delay: 0.5s;
        opacity: 0;
    }

    .hero-right {
        animation: fadeInSlideUp 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        animation-delay: 1s;
        opacity: 0;
    }

    .hero-title,
    .hero-subtitle,
    .hero-tagline,
    .btn-action {
        animation: fadeInSlideUp 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        opacity: 0;
    }

    .hero-title {
        animation-delay: 1.5s;
    }

    .hero-subtitle {
        animation-delay: 1.7s;
    }

    .hero-tagline {
        animation-delay: 1.9s;
    }

    .btn-action:nth-of-type(1) {
        animation-delay: 2.1s;
    }

    .btn-action:nth-of-type(2) {
        animation-delay: 2.3s;
    }

    /* Hover effect on buttons */
    .btn-action:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="hero-section">
    <div class="logo-fintrack">
        <img src="{{ asset('foto/logo.png') }}" alt="FinTrack Logo">
        <h4 class="fw-bold mb-0">FinTrack</h4>
    </div>
    <div class="hero-left">
        <img src="{{ asset('foto/Gambar Animasi.png') }}" alt="Ilustrasi" style="width: 100%; max-width: 700px;">
    </div>
    <div class="hero-right">
        <h1 class="hero-title">FinTrack</h1>
        <p class="hero-subtitle">Kelola uangmu sejak dini dengan mudah dan seru.</p>
        <p class="hero-tagline">Langkah kecil hari ini, bebas finansial nanti.</p>
        <div class="d-flex flex-column gap-3 align-items-center">
            <a href="{{ route('login') }}" class="btn btn-primary-custom btn-action">Login/ Daftar</a>
            <a href="{{ route('landing.page') }}" class="btn btn-secondary-custom btn-action">Lihat Fitur</a>
        </div>
    </div>
</div>
@endsection