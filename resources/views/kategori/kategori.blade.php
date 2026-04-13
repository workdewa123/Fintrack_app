@extends('layout.app')

@section('head')
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
@endsection

@section('content')
<style>
    /* --- Base Styling --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    /* --- Header Section --- */
    .kategori-header {
        background-color: #4a90e2;
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
    }

    /* --- Tabs Navigation --- */
    .tabs-container .nav {
        border-bottom: 1px solid #e2e8f0;
    }

    .tabs-container .nav-link {
        color: #64748b;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }

    .tabs-container .nav-link.active {
        color: #3b82f6;
        border-bottom: 3px solid #3b82f6;
    }

    /* --- Table Styling --- */
    .table-container {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table thead th {
        background-color: #f1f5f9;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
        padding: 1rem;
        border: none;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* --- Badge Styles --- */
    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

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

    /* --- Action Buttons --- */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        background: white;
    }

    .btn-action:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
    }

    .btn-primary-custom {
        background-color: #4a90e2;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 50rem;
        transition: all 0.2s;
    }

    .btn-primary-custom:hover {
        background-color: #2563eb;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* --- Modal Custom Styling --- */
    .modal-confirm {
        color: #636363;
        width: 400px;
    }

    .modal-confirm .modal-content {
        padding: 20px;
        border-radius: 20px;
        border: none;
        text-align: center;
    }

    .modal-confirm .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fee2e2;
        color: #ef4444;
        font-size: 46px;
    }
</style>

<div class="container-fluid py-4 px-md-4">
    <div class="mb-4">
        <h3 class="fw-bold">Klasifikasi Keuangan</h3>
        <p class="text-muted">Kelola kategori untuk merapikan setiap transaksi Anda</p>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card kategori-header text-center shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Total Kategori Aktif</h5>
                    <h1 class="fw-bold display-5 text-white" id="totalKategoriCount">0</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="tabs-container">
            <ul class="nav">
                <li class="nav-item"><a class="nav-link active" data-type="semua">Semua</a></li>
                <li class="nav-item"><a class="nav-link" data-type="MASUK">Pemasukan</a></li>
                <li class="nav-item"><a class="nav-link" data-type="KELUAR">Pengeluaran</a></li>
            </ul>
        </div>
        <button class="btn btn-primary-custom text-white d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
            <iconify-icon icon="ic:round-plus" style="font-size: 1.25rem;"></iconify-icon> Tambah Kategori
        </button>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Kategori</th>
                        <th>Tipe Transaksi</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kategoriTableBody">
                    </tbody>
            </table>

            <div id="emptyState" class="text-center py-5" style="display: none;">
                <iconify-icon icon="line-md:coffee-loop" style="font-size: 3rem;" class="text-muted mb-2"></iconify-icon>
                <p class="text-muted mb-0">Tidak ada kategori yang ditemukan.</p>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
            <div id="paginationInfo" class="text-muted small fw-medium">Memuat data...</div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="paginationLinks"></ul>
            </nav>
        </div>
    </div>
</div>

@include('kategori.modals.tambah_kategori')
@include('kategori.modals.edit_kategori')

<div id="confirmDeleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-confirm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 justify-content-center">
                <div class="icon-box">
                    <iconify-icon icon="line-md:alert-circle-loop"></iconify-icon>
                </div>
            </div>
            <div class="modal-body p-0">
                <h4 class="fw-bold">Apakah Anda yakin?</h4>
                <p class="text-muted px-3">Data kategori yang dihapus tidak dapat dikembalikan. Ini mungkin mempengaruhi histori transaksi Anda.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2">
                <button type="button" class="btn btn-secondary px-4 text-secondary border-0" style="background: #e2e8f0; border-radius: 12px; font-weight: 600; padding: 10px 20px;" data-bs-dismiss="modal">Batal</button>
                <form id="deleteKategoriForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4" style="background: #ef4444; border-radius: 12px; font-weight: 600; padding: 10px 20px;">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow" style="border-radius: 1.5rem !important;">
            <div class="modal-body text-center p-4">
                <div class="mb-3" id="iconContainer" style="width: 60px; height: 60px; background-color: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.75rem;">
                    <iconify-icon id="modalIcon" icon="line-md:confirm-circle-twotone"></iconify-icon>
                </div>
                <h5 class="fw-bold mb-2" id="successTitle">Berhasil!</h5>
                <p class="text-muted mb-4" id="successMessage">Data telah disimpan.</p>
                <div class="d-grid">
                    <button type="button" class="btn btn-primary-custom text-white rounded-pill py-2" data-bs-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- Global State ---
    let currentPage = 1;
    let currentFilter = 'semua';

    // --- Core Functions ---
    function showSuccessModal(title, message, isError = false) {
        const titleElem = document.getElementById('successTitle');
        const messageElem = document.getElementById('successMessage');
        const iconContainer = document.getElementById('iconContainer');
        const modalIcon = document.getElementById('modalIcon');

        titleElem.textContent = title;
        messageElem.textContent = message;

        if (isError) {
            iconContainer.style.backgroundColor = '#fef2f2';
            iconContainer.style.color = '#ef4444';
            modalIcon.setAttribute('icon', 'line-md:close-circle-twotone');
        } else {
            iconContainer.style.backgroundColor = '#ecfdf5';
            iconContainer.style.color = '#10b981';
            modalIcon.setAttribute('icon', 'line-md:confirm-circle-twotone');
        }

        new bootstrap.Modal(document.getElementById('successModal')).show();
    }

    function loadKategoriData(page = 1) {
        currentPage = page;
        fetch(`/kategori/kategori-data?page=${page}&tipe=${currentFilter}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalKategoriCount').textContent = data.total;
                renderKategoriTable(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error('Error:', err);
                document.getElementById('kategoriTableBody').innerHTML = '<tr><td colspan="3" class="text-center text-danger py-4">Gagal memuat data.</td></tr>';
            });
    }

    // --- Table Rendering ---
    function renderKategoriTable(categories) {
        const tbody = document.getElementById('kategoriTableBody');
        const emptyState = document.getElementById('emptyState');
        tbody.innerHTML = '';

        if (categories.length === 0) {
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';
        categories.forEach(kat => {
            const isMasuk = kat.tipe === 'MASUK';
            tbody.innerHTML += `
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center">
                        <div class="btn-action me-3 border-0 bg-light">
                            <iconify-icon icon="ic:round-label" class="${isMasuk ? 'text-success' : 'text-danger'}" style="font-size: 1.2rem;"></iconify-icon>
                        </div>
                        <span class="fw-bold text-dark">${kat.nama_kategori}</span>
                    </div>
                </td>
                <td>
                    <span class="badge-custom ${isMasuk ? 'badge-income' : 'badge-expense'} d-inline-flex align-items-center gap-1">
                        <iconify-icon icon="${isMasuk ? 'ic:round-trending-up' : 'ic:round-trending-down'}"></iconify-icon>
                        ${isMasuk ? 'Pemasukan' : 'Pengeluaran'}
                    </span>
                </td>
                <td class="text-end pe-4">
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn-action" title="Edit" onclick="editKategori(${kat.id_kategori})">
                            <iconify-icon icon="ic:round-edit" class="text-primary"></iconify-icon>
                        </button>
                        <button type="button" class="btn-action" title="Hapus" onclick="confirmDelete(${kat.id_kategori})">
                            <iconify-icon icon="ic:round-delete" class="text-danger"></iconify-icon>
                        </button>
                    </div>
                </td>
            </tr>`;
        });
    }

    function renderPagination(data) {
        const linksContainer = document.getElementById('paginationLinks');
        const info = document.getElementById('paginationInfo');
        linksContainer.innerHTML = '';
        if (info) info.textContent = `Menampilkan ${data.from || 0} - ${data.to || 0} dari ${data.total} kategori`;

        if (data.links) {
            data.links.forEach(link => {
                let pageNum = link.url ? new URL(link.url, window.location.origin).searchParams.get('page') : null;
                linksContainer.innerHTML += `
                <li class="page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}">
                    <a class="page-link shadow-none" href="#" onclick="window.changePage(event, ${pageNum})">
                        ${link.label.replace('&laquo; Previous', '‹').replace('Next &raquo;', '›')}
                    </a>
                </li>`;
            });
        }
    }

    // --- Action Handlers ---
    window.changePage = function(e, page) {
        e.preventDefault();
        if (page && page !== currentPage) loadKategoriData(page);
    };

    window.confirmDelete = function(id) {
        document.getElementById('deleteKategoriForm').action = `/kategori/destroy/${id}`;
        new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
    };

    window.editKategori = function(id) {
        fetch(`/kategori/show/${id}`)
            .then(res => res.json())
            .then(kat => {
                document.getElementById('edit_id_kategori').value = kat.id_kategori;
                document.getElementById('edit_nama_kategori').value = kat.nama_kategori;
                document.getElementById(kat.tipe === 'MASUK' ? 'edit_typeMasuk' : 'edit_typeKeluar').checked = true;
                new bootstrap.Modal(document.getElementById('editKategoriModal')).show();
            })
            .catch(() => alert('Data tidak ditemukan'));
    };

    // --- Initialization & Form Listeners ---
    document.addEventListener('DOMContentLoaded', function() {
        loadKategoriData();

        // Filter Tabs
        document.querySelectorAll('.tabs-container .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.tabs-container .nav-link').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-type') || 'semua';
                loadKategoriData(1);
            });
        });

        // Add Form Logic
        document.getElementById('tambahKategoriForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                bootstrap.Modal.getInstance(document.getElementById('tambahKategoriModal'))?.hide();
                this.reset();
                showSuccessModal('Berhasil!', 'Kategori telah tersimpan.');
                loadKategoriData(1);
            })
            .catch(err => showSuccessModal('Gagal!', err.message, true));
        });

        // Edit Form Logic
        document.getElementById('editKategoriForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_id_kategori').value;
            fetch(`/kategori/update/${id}`, {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(() => {
                bootstrap.Modal.getInstance(document.getElementById('editKategoriModal'))?.hide();
                showSuccessModal('Berhasil!', 'Kategori telah diedit.');
                loadKategoriData(currentPage);
            })
            .catch(err => showSuccessModal('Gagal!', err.message, true));
        });

        // Delete Form Logic
        document.getElementById('deleteKategoriForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(() => {
                bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'))?.hide();
                showSuccessModal('Terhapus!', 'Kategori berhasil dihapus.');
                loadKategoriData(currentPage);
            })
            .catch(() => showSuccessModal('Gagal!', 'Terjadi kesalahan saat menghapus data.', true));
        });
    });
</script>
@endpush
