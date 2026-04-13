@extends('layout.app')

@section('content')
<style>
    /* Global Styles for Rekening Page and Modals */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .form-control,
    .form-select {
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        padding: 0.75rem 1rem;
        box-shadow: none;
        background-color: #ffffff;
    }

    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }

    .btn-primary {
        background-color: #4a90e2;
        border-color: #4a90e2;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        width: auto;
    }

    .btn-primary:hover {
        background-color: #4a90e2;
        border-color: #4a90e2;
    }

    .rekening-header {
        background-color: #4a90e2;
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
    }

    /* Table Styles */
    .table thead th {
        background-color: #f1f5f9;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
        border-top: none;
        padding: 1rem;
    }

    .table tbody td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .account-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 1.1rem;
    }

    /* Modal Success Styles (Custom) */
    .modal-success-icon {
        width: 60px;
        height: 60px;
        background-color: #ecfdf5;
        color: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.75rem;
    }
</style>

<div class="rekening-container">
    <div class="mb-4">
        <h3 class="fw-bold">Kelola Rekening</h3>
        <p class="text-muted">Lihat, kelola, dan pindahkan dana antar rekeningmu dengan mudah</p>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card rekening-header text-center shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Total Saldo Saat Ini</h5>
                    <h1 class="fw-bold display-5 text-white" id="totalSaldo">Rp. 0</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0 rounded-end-pill" id="searchInput" placeholder="Cari Rekening...">
            </div>
        </div>
        <button class="btn btn-primary d-flex align-items-center rounded-pill" data-bs-toggle="modal" data-bs-target="#tambahRekeningModal">
            <i class="bi bi-plus me-1"></i> Tambah Rekening
        </button>
    </div>

    <div class="card shadow-sm mb-4 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Rekening</th>
                        <th>Saldo</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody id="rekeningTableBody"></tbody>
            </table>
            <div id="emptyState" class="text-center py-5" style="display: none;">
                <p class="text-muted mb-0">Belum ada rekening yang ditemukan.</p>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light">
            <div id="paginationInfo" class="text-muted small">
                Menampilkan 0 dari 0 data
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="paginationLinks">
                </ul>
            </nav>
        </div>
    </div>
</div>

{{-- MODALS --}}
@include('rekening.modals.tambah_rekening')
@include('rekening.modals.edit_rekening')
@include('rekening.modals.hapus_rekening')

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="modal-success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h5 class="fw-bold mb-2" id="successTitle">Berhasil!</h5>
                <p class="text-muted mb-4" id="successMessage">Data telah disimpan.</p>
                <div class="d-grid">
                    <button type="button" class="btn btn-primary rounded-pill py-2" data-bs-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let searchQuery = '';

    function showSuccessModal(title, message) {
        document.getElementById('successTitle').textContent = title;
        document.getElementById('successMessage').textContent = message;
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadRekeningData();

        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                searchQuery = e.target.value;
                loadRekeningData(1);
            });
        }

        // --- LOGIKA SIMPAN PERUBAHAN (EDIT) ---
        const editForm = document.getElementById('editRekeningForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Ambil ID dari input hidden di dalam form edit
                const id = document.getElementById('editRekeningId').value;
                const formData = new FormData(this);

                fetch(`/rekening/${id}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => {
                            throw err;
                        });
                        return response.json();
                    })

                    .then(data => {
                        const modalElem = document.getElementById('editRekeningModal');
                        const modal = bootstrap.Modal.getInstance(modalElem);

                        if (modal) {
                            modal.hide();
                        }
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        showSuccessModal('Berhasil!', 'Perubahan pada rekening telah disimpan.');
                        loadRekeningData(currentPage);
                    })




                    .catch(err => {
                        console.error('Error:', err);
                        showSuccessModal('Gagal!', 'Terjadi kesalahan: ' + (err.message || 'Gagal menyimpan data.'));
                    });
            });
        }

        // --- LOGIKA TAMBAH REKENING BARU ---
        const tambahForm = document.getElementById('tambahRekeningForm');
        if (tambahForm) {
            tambahForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('/rekening', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal menambah rekening');
                        return response.json();
                    })
                    .then(data => {
                        const modalElem = document.getElementById('tambahRekeningModal');
                        const modal = bootstrap.Modal.getInstance(modalElem);
                        if (modal) modal.hide();

                        tambahForm.reset();
                        showSuccessModal('Berhasil!', 'Rekening baru Anda telah berhasil ditambahkan.');
                        loadRekeningData(1);
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        showSuccessModal('Gagal!', 'Terjadi kesalahan saat menambah data.');
                    });
            });
        }

        // Logika Klik Ikon & Warna di Modal (Tetap Ada)
        const setupPickers = (modalId, iconInput, colorInput) => {
            document.querySelectorAll(`${modalId} .icon-item`).forEach(item => {
                item.addEventListener('click', function() {
                    document.getElementById(iconInput).value = this.dataset.icon;
                    document.querySelectorAll(`${modalId} .icon-item`).forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            document.querySelectorAll(`${modalId} .color-item`).forEach(item => {
                item.addEventListener('click', function() {
                    document.getElementById(colorInput).value = this.dataset.color;
                    document.querySelectorAll(`${modalId} .color-item`).forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        };

        setupPickers('#tambahRekeningModal', 'selectedIcon', 'selectedColor');
        setupPickers('#editRekeningModal', 'editSelectedIcon', 'editSelectedColor');
    });

    function loadRekeningData(page = 1) {
        currentPage = page;
        fetch(`/rekening-data?page=${page}&search=${searchQuery}`)
            .then(response => response.json())
            .then(data => {
                renderRekeningTable(data.data);
                renderPagination(data);
                if (data.total_semua_rekening !== undefined) {
                    const formattedTotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(data.total_semua_rekening);
                    document.getElementById('totalSaldo').textContent = formattedTotal;
                }
            })
            .catch(err => console.error('Error:', err));
    }

    function renderRekeningTable(rekenings) {
        const tbody = document.getElementById('rekeningTableBody');
        const emptyState = document.getElementById('emptyState');
        tbody.innerHTML = '';

        if (!rekenings || rekenings.length === 0) {
            emptyState.style.display = 'block';
            return;
        }

        emptyState.style.display = 'none';
        rekenings.forEach(rek => {
            tbody.innerHTML += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="account-icon me-3" style="background-color: ${rek.warna || '#4a90e2'}">
                                <i class="bi ${rek.icon || 'bi-wallet2'}"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">${rek.nama_rekening}</div>
                                <div class="text-muted small">Personal</div>
                            </div>
                        </div>
                    </td>
                    <td class="fw-bold text-dark">Rp ${new Intl.NumberFormat('id-ID').format(rek.saldo)}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-sm btn-light border" onclick="editRekening(${rek.id_rekening})">
                                <i class="bi bi-pencil text-primary"></i>
                            </button>
                            <button class="btn btn-sm btn-light border" onclick="deleteRekening(${rek.id_rekening})">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    window.editRekening = function(id) {
        fetch(`/rekening/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Data tidak ditemukan');
                return response.json();
            })
            .then(rek => {
                document.getElementById('editRekeningId').value = rek.id_rekening;
                document.getElementById('editNamaRekening').value = rek.nama_rekening;
                document.getElementById('editSaldoAwal').value = rek.saldo;
                document.getElementById('editSelectedIcon').value = rek.icon;
                document.getElementById('editSelectedColor').value = rek.warna;

                document.querySelectorAll('#editRekeningModal .icon-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.dataset.icon === rek.icon) item.classList.add('active');
                });
                document.querySelectorAll('#editRekeningModal .color-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.dataset.color === rek.warna) item.classList.add('active');
                });

                const modalElem = document.getElementById('editRekeningModal');
                const modal = new bootstrap.Modal(modalElem);
                modal.show();
            })
            .catch(err => alert(err.message));
    };

    window.deleteRekening = function(id) {
        fetch(`/rekening/${id}`)
            .then(response => response.json())
            .then(rek => {
                document.getElementById('deleteRekeningId').value = rek.id_rekening;
                document.getElementById('hapusRekeningNama').textContent = rek.nama_rekening;
                const modal = new bootstrap.Modal(document.getElementById('hapusRekeningModal'));
                modal.show();
            });
    };

    window.executeDelete = function() {
        const id = document.getElementById('deleteRekeningId').value;
        fetch(`/rekening/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('hapusRekeningModal'));
                if (modal) modal.hide();
                showSuccessModal('Terhapus!', 'Rekening berhasil dihapus.');
                loadRekeningData(currentPage);
            });
    };

    function renderPagination(data) {
        const linksContainer = document.getElementById('paginationLinks');
        const info = document.getElementById('paginationInfo');
        linksContainer.innerHTML = '';
        info.textContent = `Menampilkan ${data.from || 0} - ${data.to || 0} dari ${data.total} rekening`;

        if (data.links) {
            data.links.forEach(link => {
                const isActive = link.active ? 'active' : '';
                const isDisabled = !link.url ? 'disabled' : '';
                const label = link.label.replace('&laquo; Previous', '‹').replace('Next &raquo;', '›');
                linksContainer.innerHTML += `
                    <li class="page-item ${isActive} ${isDisabled}">
                        <a class="page-link" href="#" onclick="changePage(event, '${link.url}')">${label}</a>
                    </li>
                `;
            });
        }
    }

    window.changePage = function(e, url) {
        e.preventDefault();
        if (url) {
            fetch(url + `&search=${searchQuery}`)
                .then(response => response.json())
                .then(data => {
                    renderRekeningTable(data.data);
                    renderPagination(data);
                });
        }
    };
</script>
@endpush
