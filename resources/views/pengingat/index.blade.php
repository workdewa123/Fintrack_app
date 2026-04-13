@extends('layout.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- Base Styling --- */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    /* --- Tabs Styling --- */
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
        text-decoration: none;
    }

    .tabs-container .nav-link.active {
        color: #4a90e2;
        border-bottom: 3px solid #4a90e2;
    }

    /* --- Table Styling --- */
    .table-container {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table thead tr {
        background-color: #f1f5f9;
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

    /* --- UI Components --- */
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

    .btn-primary-custom {
        background-color: #4a90e2;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 50rem;
        transition: all 0.2s;
        color: white;
        text-decoration: none;
    }

    /* --- Statistics Card --- */
    .card-stat-blue {
        background-color: #4a90e2;
        color: white;
        border-radius: 1rem;
        border: none;
        padding: 3rem 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .card-text-custom {
        font-size: 4rem !important;
        font-weight: 800;
        margin-top: 0.5rem;
    }

    /* --- Validation Styling --- */
    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545 !important;
        background-image: none !important;
    }

    .form-control.is-invalid:focus,
    .form-select.is-invalid:focus {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
    }
</style>

<div class="container-fluid py-4 px-md-4">
    <div class="mb-4">
        <h3 class="fw-bold">Riwayat Pembayaran Reguler</h3>
        <p class="text-muted">Kelola pengingat transaksi otomatis Anda di sini</p>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-stat-blue shadow-sm">
                <h5 class="fw-bold mb-0">Total Pengingat Aktif</h5>
                <h1 class="card-text-custom" id="statTotalCount">0</h1>
            </div>
        </div>
    </div>

    <div class="row mb-4 align-items-center g-3">
        <div class="col-md-7">
            <div class="tabs-container">
                <nav class="nav">
                    <a class="nav-link active" data-frekuensi="semua">Semua</a>
                    <a class="nav-link" data-frekuensi="HARIAN">Harian</a>
                    <a class="nav-link" data-frekuensi="MINGGUAN">Mingguan</a>
                    <a class="nav-link" data-frekuensi="BULANAN">Bulanan</a>
                </nav>
            </div>
        </div>
        <div class="col-md-5">
            <div class="d-flex gap-2 justify-content-md-end">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border" style="max-width: 250px;">
                    <span class="input-group-text bg-white border-0 ps-3">
                        <iconify-icon icon="ic:round-search" class="text-muted"></iconify-icon>
                    </span>
                    <input type="text" id="pengingatSearch" class="form-control border-0 py-2 shadow-none" placeholder="Cari pengingat...">
                </div>
                <button class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addPengingatModal">
                    <iconify-icon icon="ic:round-plus" style="font-size: 1.25rem;"></iconify-icon> Baru
                </button>
            </div>
        </div>
    </div>

    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Pembayaran</th>
                        <th>Frekuensi</th>
                        <th>Jumlah</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pengingatTableBody"></tbody>
            </table>

            <div id="emptyState" class="text-center py-5" style="display: none;">
                <iconify-icon icon="line-md:calendar-remove" style="font-size: 3rem;" class="text-muted mb-2"></iconify-icon>
                <p class="text-muted mb-0">Belum ada pengingat yang ditemukan.</p>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
            <div id="paginationInfo" class="text-muted small fw-medium">Memuat...</div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="paginationLinks"></ul>
            </nav>
        </div>
    </div>
</div>

@include('pengingat.modals.tambah_pengingat')
@include('pengingat.modals.edit_pengingat')
@include('pengingat.modals.detail_pengingat')

<div id="confirmDeleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-confirm modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 justify-content-center pt-4">
                <div class="icon-box" style="width: 80px; height: 80px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 46px;">
                    <iconify-icon icon="line-md:alert-circle-loop"></iconify-icon>
                </div>
            </div>
            <div class="modal-body text-center p-0">
                <h4 class="fw-bold">Apakah Anda yakin?</h4>
                <p class="text-muted px-3">Pengingat ini akan dihapus permanen. Aksi ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 12px;">Batal</button>
                <form id="deletePengingatForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4" style="border-radius: 12px; background: #ef4444; border: none;">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModalCustom" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="border-radius: 1.5rem !important;">
            <div class="modal-body text-center p-4">
                <div class="mb-3" id="iconContainerCustom" style="width: 60px; height: 60px; background-color: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 1.75rem;">
                    <iconify-icon id="modalIconCustom" icon="line-md:confirm-circle-twotone"></iconify-icon>
                </div>
                <h5 class="fw-bold mb-2" id="successTitleCustom">Berhasil!</h5>
                <p class="text-muted mb-4" id="successMessageCustom">Data telah diperbarui.</p>
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
    // --- Global Configuration ---
    let currentFrekuensi = 'semua';
    let searchQuery = '';
    let currentPage = 1;
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    });

    // --- Core Data Loading ---
    function loadPengingat(page = 1) {
        currentPage = page;
        fetch(`/pembayaran-reguler/data?page=${page}&frekuensi=${currentFrekuensi}&search=${searchQuery}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('statTotalCount').innerText = data.total || 0;
                renderTable(data.data);
                renderPagination(data);
            })
            .catch(err => console.error("Gagal memuat data:", err));
    }

    function loadRekeningToModal(targetId) {
        fetch('/api/rekening-all')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById(targetId);
                if (!select) return;
                select.innerHTML = '<option disabled selected>Pilih Rekening</option>';
                data.forEach(r => {
                    select.innerHTML += `<option value="${r.id_rekening}">${r.nama_rekening} (${formatter.format(r.saldo)})</option>`;
                });
            });
    }

    function loadKategoriPengeluaranOnly(targetId) {
        fetch('/api/kategori-all')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById(targetId);
                if (!select) return;
                select.innerHTML = '<option disabled selected>Pilih Kategori</option>';
                const filtered = data.filter(k => k.tipe === 'KELUAR' || k.tipe === 'Pengeluaran');
                filtered.forEach(k => {
                    select.innerHTML += `<option value="${k.id_kategori}">${k.nama_kategori}</option>`;
                });
            });
    }

    // --- Table Rendering ---
    function renderTable(items) {
        const body = document.getElementById('pengingatTableBody');
        const empty = document.getElementById('emptyState');
        if (!items || items.length === 0) {
            body.innerHTML = '';
            empty.style.display = 'block';
            return;
        }
        empty.style.display = 'none';
        body.innerHTML = items.map(item => `
            <tr>
                <td class="ps-4 fw-bold text-dark">${item.nama_pembayaran}</td>
                <td><span class="text-muted"><i class="bi bi-clock-history me-1"></i> ${item.frekuensi}</span></td>
                <td class="fw-bold text-danger">${formatter.format(item.jumlah)}</td>
                <td class="text-end pe-4">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn-action" onclick="detailPengingat(${item.id_pengingat})" title="Detail">
                            <iconify-icon icon="ic:round-remove-red-eye" class="text-primary"></iconify-icon>
                        </button>
                        <button class="btn-action" onclick="editPengingat(${item.id_pengingat})" title="Edit">
                            <i class="bi bi-pencil text-primary"></i>
                        </button>
                        <button type="button" class="btn-action" onclick="confirmDelete(${item.id_pengingat})" title="Hapus">
                            <i class="bi bi-trash text-danger"></i>
                        </button>
                    </div>
                </td>
            </tr>`).join('');
    }

    function renderPagination(data) {
        const info = document.getElementById('paginationInfo');
        const links = document.getElementById('paginationLinks');
        info.innerText = `Menampilkan ${data.from || 0} - ${data.to || 0} dari ${data.total || 0}`;
        links.innerHTML = (data.links || []).map(link => {
            const page = link.url ? new URLSearchParams(link.url.split('?')[1]).get('page') : null;
            return `<li class="page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}">
                <a class="page-link shadow-none" href="#" onclick="event.preventDefault(); if(${page}) loadPengingat(${page})">
                    ${link.label.replace('&laquo; Previous', '‹').replace('Next &raquo;', '›')}
                </a></li>`;
        }).join('');
    }

    // --- Action Functions ---
    window.confirmDelete = function(id) {
        Swal.fire({
            title: 'Hapus Pengingat?',
            text: "Data ini tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pembayaran-reguler/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    window.toggleDetailJadwal = function(val, containerId, selectedDetail = null) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';

        if (val === 'MINGGUAN') {
            container.innerHTML = `
                <label class="form-label small fw-bold text-primary">Pilih Hari Notifikasi</label>
                <select name="detail_jadwal" class="form-select" required>
                    <option value="1" ${selectedDetail == 1 ? 'selected' : ''}>Senin</option>
                    <option value="2" ${selectedDetail == 2 ? 'selected' : ''}>Selasa</option>
                    <option value="3" ${selectedDetail == 3 ? 'selected' : ''}>Rabu</option>
                    <option value="4" ${selectedDetail == 4 ? 'selected' : ''}>Kamis</option>
                    <option value="5" ${selectedDetail == 5 ? 'selected' : ''}>Jumat</option>
                    <option value="6" ${selectedDetail == 6 ? 'selected' : ''}>Sabtu</option>
                    <option value="7" ${selectedDetail == 7 ? 'selected' : ''}>Minggu</option>
                </select>
                <small class="text-muted">Notifikasi akan muncul setiap hari yang dipilih.</small>
            `;
        } else if (val === 'BULANAN') {
            let options = '';
            for (let i = 1; i <= 31; i++) {
                options += `<option value="${i}" ${selectedDetail == i ? 'selected' : ''}>Tanggal ${i}</option>`;
            }
            container.innerHTML = `
                <label class="form-label small fw-bold text-primary">Pilih Tanggal Notifikasi</label>
                <select name="detail_jadwal" class="form-select" required>
                    ${options}
                </select>
                <small class="text-muted">Notifikasi akan muncul setiap bulan pada tanggal ini.</small>
            `;
        }
    };

    window.detailPengingat = function(id) {
        fetch(`/pembayaran-reguler/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('detNama').innerText = data.nama_pembayaran;
                document.getElementById('detJumlah').innerText = formatter.format(data.jumlah);
                document.getElementById('detFrekuensi').innerText = data.frekuensi;
                document.getElementById('detKategori').innerText = data.kategori?.nama_kategori || '-';
                document.getElementById('detRekening').innerText = data.rekening?.nama_rekening || '-';
                document.getElementById('detTglMulai').innerText = data.tanggal_mulai;
                document.getElementById('detTglAkhir').innerText = data.tanggal_akhir || 'Selamanya';
                document.getElementById('detKomentar').innerText = data.komentar || '-';
                new bootstrap.Modal(document.getElementById('detailPengingatModal')).show();
            });
    };

    window.editPengingat = function(id) {
        fetch(`/pembayaran-reguler/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editId').value = data.id_pengingat;
                document.getElementById('editNama').value = data.nama_pembayaran;
                document.getElementById('editFrekuensi').value = data.frekuensi;
                document.getElementById('editJumlah').value = data.jumlah;
                document.getElementById('editTglMulai').value = data.tanggal_mulai;
                document.getElementById('editTglAkhir').value = data.tanggal_akhir || '';
                document.getElementById('editKomentar').value = data.komentar || '';

                loadRekeningToModal('editRekening', data.id_rekening);
                loadKategoriPengeluaranOnly('editKategori', data.id_kategori);
                toggleDetailJadwal(data.frekuensi, 'containerDetailEdit', data.detail_jadwal);

                new bootstrap.Modal(document.getElementById('editPengingatModal')).show();
            });
    };

    // --- Initialization & Event Listeners ---
    document.addEventListener('DOMContentLoaded', function() {
        loadPengingat(1);

        // Add Modal Events
        const addModal = document.getElementById('addPengingatModal');
        if (addModal) {
            addModal.addEventListener('show.bs.modal', () => {
                loadRekeningToModal('modalRekening');
                loadKategoriPengeluaranOnly('modalKategori');
            });
        }

        // Add Form Logic
        const addForm = document.getElementById('addPengingatForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const requiredFields = addForm.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire('Perhatian!', 'Harap lengkapi semua bidang yang wajib diisi!', 'warning');
                    return;
                }

                const formData = new FormData(this);
                fetch("{{ route('pembayaran.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(addModal).hide();
                        addForm.reset();
                        loadPengingat(1);
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Pengingat berhasil disimpan.', showConfirmButton: false, timer: 1500 });
                    } else {
                        Swal.fire('Gagal!', data.message || 'Harap periksa kembali inputan Anda.', 'error');
                    }
                });
            });
        }

        // Edit Form Logic
        const editForm = document.getElementById('editPengingatForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const requiredFields = editForm.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire('Perhatian!', 'Harap lengkapi semua bidang yang wajib diisi!', 'warning');
                    return;
                }

                const id = document.getElementById('editId').value;
                const formData = Object.fromEntries(new FormData(this));

                fetch(`/pembayaran-reguler/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editPengingatModal')).hide();
                        loadPengingat(currentPage);
                        Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data berhasil diperbarui.', showConfirmButton: false, timer: 1500 });
                    } else {
                        Swal.fire('Gagal!', data.message || 'Periksa kembali data Anda.', 'error');
                    }
                });
            });
        }

        // Tabs & Search Interactions
        document.querySelectorAll('.tabs-container .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.tabs-container .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                currentFrekuensi = this.dataset.frekuensi;
                loadPengingat(1);
            });
        });

        let timer;
        document.getElementById('pengingatSearch').addEventListener('input', (e) => {
            clearTimeout(timer);
            searchQuery = e.target.value;
            timer = setTimeout(() => loadPengingat(1), 500);
        });

        // Flash Message Handling
        @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Dihapus!', text: "{{ session('success') }}", showConfirmButton: false, timer: 1500 });
        @endif
    });
</script>
@endpush
