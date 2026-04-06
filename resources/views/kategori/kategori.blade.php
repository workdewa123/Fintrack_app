@extends('layout.app')

@section('head')
{{-- Memuat pustaka Iconify dari CDN --}}
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    /* Tabs Styling */
    .tabs-container .nav-link {
        color: #a0aec0;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }

    .tabs-container .nav-link.active {
        color: #3b82f6;
        border-bottom: 2px solid #3b82f6;
    }

    /* Table Container Styling */
    .table-container {
        background: white;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Badge Customization */
    .badge-income {
        background-color: #e6fffa;
        color: #047857;
        border: 1px solid #b2f5ea;
    }

    .badge-expense {
        background-color: #fff5f5;
        color: #c53030;
        border: 1px solid #feb2b2;
    }

    /* Profile Styling */
    .user-profile-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #edf2f7;
        cursor: pointer;
        overflow: hidden;
        display: inline-block;
    }

    .user-profile-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .tiara-icon {
        font-size: 1.5rem;
        color: gold;
        margin-left: -10px;
        z-index: 10;
        transform: rotate(20deg);
    }

    .hidden-input {
        display: none;
    }
</style>

<div class="container-fluid p-5">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Daftar Kategori</h2>
            <p class="text-muted small">Kelola klasifikasi transaksi keuangan Anda</p>
        </div>
        
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-dark px-4 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
                <iconify-icon icon="ic:round-plus"></iconify-icon> Tambah
            </button>
        </div>
    </div>

    {{-- Tabs Filter --}}
    <div class="tabs-container mb-3">
        <ul class="nav">
            <li class="nav-item"><a class="nav-link active" data-filter="all">Semua</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="income">Pemasukan</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="expense">Pengeluaran</a></li>
        </ul>
    </div>

    {{-- Tabel Section --}}
    <div class="table-container">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr class="text-secondary">
                        <th class="ps-3 fw-semibold">NAMA KATEGORI</th>
                        <th class="fw-semibold">TIPE</th>
                        <th class="text-end pe-3 fw-semibold">AKSI</th>
                    </tr>
                </thead>
                <tbody id="categoryTableBody">
                    @php
                    // Array dummy data, pastikan di produksi data ini datang dari Controller ($categories)
                    $categoriesData = [
                        ['id' => 1, 'label' => 'Gaji', 'type' => 'income'],
                        ['id' => 2, 'label' => 'Bonus', 'type' => 'income'],
                        ['id' => 3, 'label' => 'Hasil Jualan', 'type' => 'income'],
                        ['id' => 4, 'label' => 'Freelance', 'type' => 'income'],
                        ['id' => 5, 'label' => 'Uang Saku', 'type' => 'income'],
                        ['id' => 8, 'label' => 'Kesehatan', 'type' => 'expense'],
                        ['id' => 9, 'label' => 'Rumah', 'type' => 'expense'],
                        ['id' => 10, 'label' => 'Cafe', 'type' => 'expense'],
                    ];
                    @endphp

                    @foreach($categoriesData as $category)
                    <tr class="category-row" data-type="{{ $category['type'] }}">
                        <td class="ps-3 fw-medium text-dark">{{ $category['label'] }}</td>
                        <td>
                            <span class="badge {{ $category['type'] == 'income' ? 'badge-income' : 'badge-expense' }} px-3 py-2 rounded-2">
                                {{ $category['type'] == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                {{-- Tombol Edit mengarah ke Modal Spesifik ID --}}
                                <button class="btn btn-sm btn-light border shadow-sm" 
                                        title="Edit"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editKategoriModal{{ $category['id'] }}">
                                    <iconify-icon icon="ic:round-edit" class="text-primary"></iconify-icon>
                                </button>
                                
                                <button class="btn btn-sm btn-light border shadow-sm" title="Hapus">
                                    <iconify-icon icon="ic:round-delete" class="text-danger"></iconify-icon>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Masukkan modal edit di dalam loop agar variabel $category terbaca --}}
                    @include('kategori.modals.edit_kategori', ['category' => $category])
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah Kategori --}}
@include('kategori.modals.tambah_kategori')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika Filter Tab
        const navLinks = document.querySelectorAll('.tabs-container .nav-link');
        const tableRows = document.querySelectorAll('#categoryTableBody .category-row');

        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(el => el.classList.remove('active'));
                this.classList.add('active');

                const filterType = this.getAttribute('data-filter');

                tableRows.forEach(row => {
                    const rowType = row.getAttribute('data-type');
                    if (filterType === 'all' || rowType === filterType) {
                        row.style.display = 'table-row';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Logika Ganti Foto Profil
        const profileImageInput = document.getElementById('profileImageInput');
        const profileImage = document.getElementById('profileImage');

        profileImageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection