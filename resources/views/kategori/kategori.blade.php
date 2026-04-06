@extends('layout.app')

@section('head')
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
    .tabs-container .nav-link { color: #a0aec0; font-weight: 600; padding: 0.5rem 1rem; border-bottom: 2px solid transparent; transition: all 0.2s; cursor: pointer; }
    .tabs-container .nav-link.active { color: #3b82f6; border-bottom: 2px solid #3b82f6; }
    .table-container { background: white; padding: 1.5rem; border-radius: 0.75rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
    .badge-income { background-color: #e6fffa; color: #047857; border: 1px solid #b2f5ea; }
    .badge-expense { background-color: #fff5f5; color: #c53030; border: 1px solid #feb2b2; }
</style>

<div class="container-fluid p-5">
    {{-- Tambahkan ini di bawah Alert Success --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Daftar Kategori</h2>
            <p class="text-muted small">Kelola klasifikasi transaksi keuangan Anda</p>
        </div>
        
        <button class="btn btn-dark px-4 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
            <iconify-icon icon="ic:round-plus"></iconify-icon> Tambah
        </button>
    </div>

    {{-- Tabs Filter --}}
    <div class="tabs-container mb-3">
        <ul class="nav">
            <li class="nav-item"><a class="nav-link active" data-filter="all">Semua</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="MASUK">Pemasukan</a></li>
            <li class="nav-item"><a class="nav-link" data-filter="KELUAR">Pengeluaran</a></li>
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
                {{-- ... bagian tabel ... --}}
                <tbody id="categoryTableBody">
                    @forelse($categories as $category)
                    <tr class="category-row" data-type="{{ $category->tipe }}">
                        <td class="ps-3 fw-medium text-dark">{{ $category->nama_kategori }}</td>
                        <td>
                            <span class="badge {{ $category->tipe == 'MASUK' ? 'badge-income' : 'badge-expense' }} px-3 py-2 rounded-2">
                                {{ $category->tipe == 'MASUK' ? 'Pemasukan' : 'Pengeluaran' }}
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-light border shadow-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editKategoriModal{{ $category->id_kategori }}">
                                    <iconify-icon icon="ic:round-edit" class="text-primary"></iconify-icon>
                                </button>
                                
                                {{-- Form Hapus --}}
                                <form action="{{ route('kategori.destroy', $category->id_kategori) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border shadow-sm">
                                        <iconify-icon icon="ic:round-delete" class="text-danger"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL HARUS DI DALAM LOOP --}}
                    @include('kategori.modals.edit_kategori', ['category' => $category])

                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('kategori.modals.tambah_kategori')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.tabs-container .nav-link');
        const tableRows = document.querySelectorAll('#categoryTableBody .category-row');

        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(el => el.classList.remove('active'));
                this.classList.add('active');

                const filterType = this.getAttribute('data-filter');
                tableRows.forEach(row => {
                    const rowType = row.getAttribute('data-type');
                    row.style.display = (filterType === 'all' || rowType === filterType) ? 'table-row' : 'none';
                });
            });
        });
    });
</script>
@endsection