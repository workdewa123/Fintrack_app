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
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }

    .btn-primary {
        background-color: #3b82f6;
        border-color: #3b82f6;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        width: auto;
    }

    .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
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

    .action-link {
        text-decoration: none;
        font-weight: 500;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        transition: all 0.2s;
        font-size: 0.875rem;
    }

    .action-link:hover {
        background-color: #f8fafc;
    }

    .icon-item, .color-item {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .icon-item {
        width: 50px; height: 50px;
        display: flex; justify-content: center; align-items: center;
        border-radius: 50%; border: 1px solid #d1d5db;
        background-color: #ffffff; color: #6b7280; font-size: 1.5rem;
    }

    .icon-item.active {
        background-color: #e0e7ff; border-color: #3b82f6; color: #3b82f6;
    }

    .color-item {
        width: 32px; height: 32px; border-radius: 50%; border: 2px solid transparent;
    }

    .color-item.active {
        border-color: #4a5568; box-shadow: 0 0 0 3px rgba(74, 85, 104, 0.2);
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

@endsection

@push('scripts')
<script>
    let currentPage = 1;
    let searchQuery = '';

    document.addEventListener('DOMContentLoaded', function() {
        loadRekeningData();

        // Fitur Pencarian (Tetap dipertahankan)
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                searchQuery = e.target.value;
                loadRekeningData(1);
            });
        }

        // --- PERBAIKAN LOGIKA SIMPAN PERUBAHAN ---
        const editForm = document.getElementById('editRekeningForm'); // ID sesuai dengan di edit_rekening.blade.php
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault(); 

                const id = document.getElementById('editRekeningId').value;
                const formData = new FormData(this);

                fetch(`/rekening/${id}`, {
                    method: 'POST', 
                    body: formData,
                    headers: {
                        // Menggunakan helper Laravel agar lebih aman dari error getAttribute null
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal menyimpan data');
                    return response.json();
                })
                .then(data => {
                    // Tutup modal menggunakan instance Bootstrap
                    const modalElem = document.getElementById('editRekeningModal');
                    const modal = bootstrap.Modal.getInstance(modalElem);
                    if (modal) modal.hide();
                    
                    alert('Rekening berhasil diperbarui!');
                    loadRekeningData(currentPage); // Refresh tabel
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Terjadi kesalahan saat menyimpan.');
                });
            });
        }

        // --- LOGIKA KLIK IKON & WARNA DI MODAL EDIT ---
        document.querySelectorAll('#editRekeningModal .icon-item').forEach(item => {
            item.addEventListener('click', function() {
                document.getElementById('editSelectedIcon').value = this.dataset.icon;
                document.querySelectorAll('#editRekeningModal .icon-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('#editRekeningModal .color-item').forEach(item => {
            item.addEventListener('click', function() {
                document.getElementById('editSelectedColor').value = this.dataset.color;
                document.querySelectorAll('#editRekeningModal .color-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // --- LOGIKA TAMBAH REKENING BARU ---
        const tambahForm = document.getElementById('tambahRekeningForm');
        if (tambahForm) {
            tambahForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah reload halaman

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
                    // Tutup modal
                    const modalElem = document.getElementById('tambahRekeningModal');
                    const modal = bootstrap.Modal.getInstance(modalElem);
                    if (modal) modal.hide();

                    // Reset Form
                    tambahForm.reset();
                    
                    alert('Rekening baru berhasil ditambahkan!');
                    loadRekeningData(1); // Refresh tabel ke halaman 1
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Terjadi kesalahan saat menambah data.');
                });
            });
        }

        // --- LOGIKA PILIH IKON & WARNA UNTUK TAMBAH REKENING ---
        // Pastikan ID 'selectedIcon' dan 'selectedColor' sesuai dengan di tambah_rekening.blade.php
        document.querySelectorAll('#tambahRekeningModal .icon-item').forEach(item => {
            item.addEventListener('click', function() {
                document.getElementById('selectedIcon').value = this.dataset.icon;
                document.querySelectorAll('#tambahRekeningModal .icon-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelectorAll('#tambahRekeningModal .color-item').forEach(item => {
            item.addEventListener('click', function() {
                document.getElementById('selectedColor').value = this.dataset.color;
                document.querySelectorAll('#tambahRekeningModal .color-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });

    // --- FUNGSI LOAD DATA ---
    function loadRekeningData(page = 1) {
        currentPage = page;
        fetch(`/rekening-data?page=${page}&search=${searchQuery}`)
            .then(response => response.json())
            .then(data => {
                renderRekeningTable(data.data);
                renderPagination(data);
                
                // --- BAGIAN YANG DIPERBAIKI ---
                if(data.total_semua_rekening !== undefined) {
                    // Gunakan Intl.NumberFormat untuk memformat angka ke Rupiah
                    const formattedTotal = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(data.total_semua_rekening);
                    
                    document.getElementById('totalSaldo').textContent = formattedTotal;
                }
                // ------------------------------
            })
            .catch(err => console.error('Error:', err));
    }

    // --- FUNGSI RENDER TABEL (Tetap dipertahankan) ---
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

    // --- PERBAIKAN FUNGSI EDIT REKENING (PENGISIAN DATA) ---
    window.editRekening = function(id) {
        fetch(`/rekening/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Data tidak ditemukan');
                return response.json();
            })
            .then(rek => {
                // Pastikan ID ini sesuai dengan elemen di edit_rekening.blade.php
                document.getElementById('editRekeningId').value = rek.id_rekening;
                document.getElementById('editNamaRekening').value = rek.nama_rekening;
                document.getElementById('editSaldoAwal').value = rek.saldo;
                document.getElementById('editSelectedIcon').value = rek.icon;
                document.getElementById('editSelectedColor').value = rek.warna;

                // Update visual Ikon Aktif
                document.querySelectorAll('#editRekeningModal .icon-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.dataset.icon === rek.icon) item.classList.add('active');
                });

                // Update visual Warna Aktif
                document.querySelectorAll('#editRekeningModal .color-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.dataset.color === rek.warna) item.classList.add('active');
                });

                // Tampilkan modal
                const modalElem = document.getElementById('editRekeningModal');
                const modal = new bootstrap.Modal(modalElem);
                modal.show();
            })
            .catch(err => alert(err.message));
    };

    // --- FUNGSI HAPUS REKENING ---
window.deleteRekening = function(id) {
    // Set ID ke dalam input hidden yang ada di modal hapus
    // Pastikan di modal hapus Anda memiliki input: <input type="hidden" id="deleteRekeningId">
    const deleteInput = document.getElementById('deleteRekeningId');
    if (deleteInput) {
        deleteInput.value = id;
    }

    // Tampilkan modal konfirmasi hapus
    // Pastikan ID modal di hapus_rekening.blade.php adalah 'hapusRekeningModal'
    const modalElem = document.getElementById('hapusRekeningModal');
    if (modalElem) {
        const hapusModal = new bootstrap.Modal(modalElem);
        hapusModal.show();
    } else {
        // Jika modal tidak ditemukan, gunakan konfirmasi browser biasa sebagai cadangan
        if (confirm('Apakah Anda yakin ingin menghapus rekening ini?')) {
            executeDelete(id);
        }
    }
};

// --- 1. FUNGSI UNTUK MEMBUKA MODAL HAPUS ---
window.deleteRekening = function(id) {
    fetch(`/rekening/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Data tidak ditemukan');
            return response.json();
        })
        .then(rek => {
            // Isi ID ke input hidden (ID sesuai modal hapus)
            const idInput = document.getElementById('deleteRekeningId');
            const nameSpan = document.getElementById('hapusRekeningNama');
            
            if (idInput) idInput.value = rek.id_rekening;
            if (nameSpan) nameSpan.textContent = rek.nama_rekening;

            // Munculkan Modal
            const modalElem = document.getElementById('hapusRekeningModal');
            if (modalElem) {
                const hapusModal = new bootstrap.Modal(modalElem);
                hapusModal.show();
            }
        })
        .catch(err => console.error('Gagal memuat data hapus:', err));
};

// --- 2. FUNGSI UNTUK EKSEKUSI HAPUS KE SERVER ---
window.executeDelete = function() {
    const idInput = document.getElementById('deleteRekeningId');
    if (!idInput || !idInput.value) {
        alert('ID Rekening tidak ditemukan!');
        return;
    }

    const id = idInput.value;

    fetch(`/rekening/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Gagal menghapus data');
        return response.json();
    })
    .then(data => {
        // Tutup modal
        const modalElem = document.getElementById('hapusRekeningModal');
        const modal = bootstrap.Modal.getInstance(modalElem);
        if (modal) modal.hide();

        alert('Rekening berhasil dihapus!');
        loadRekeningData(currentPage); // Refresh tabel otomatis
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Terjadi kesalahan saat menghapus data.');
    });
};
    // --- FUNGSI PAGINATION (Tetap dipertahankan) ---
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