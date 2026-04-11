@extends('layout.app')

@section('content')
<style>
    /* Konsistensi dengan Halaman Transaksi */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    /* Tabs Styling */
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
        color: #3b82f6;
        border-bottom: 3px solid #3b82f6;
    }

    /* Table Styles */
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

    /* Badge & Button Styles */
    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-income { background-color: #e6fffa; color: #047857; border: 1px solid #b2f5ea; }
    .badge-expense { background-color: #fff5f5; color: #c53030; border: 1px solid #feb2b2; }

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
        background-color: #3b82f6;
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 50rem;
        transition: all 0.2s;
        color: white;
        text-decoration: none;
    }

    .card-stat-blue {
        background-color: #4a90e2; /* Sesuaikan dengan warna biru di gambar */
        color: white;
        border-radius: 1rem;
        border: none;
        padding: 3rem 1rem; /* Memberikan ruang vertikal yang lebih luas */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .card-text-custom {
        font-size: 4rem !important; /* Ukuran angka yang besar sesuai gambar */
        font-weight: 800;
        margin-top: 0.5rem;
    }

    /* Highlight untuk input yang kosong */
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545 !important;
        background-image: none !important;
    }

    .form-control.is-invalid:focus, .form-select.is-invalid:focus {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
    }
</style>

<div class="container-fluid py-4 px-md-4">
    <div class="mb-4">
        <h3 class="fw-bold">Riwayat Pembayaran Reguler</h3>
        <p class="text-muted">Kelola pengingat transaksi otomatis Anda di sini</p>
    </div>

    {{-- Statistik Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-stat-blue shadow-sm">
                <h5 class="fw-bold mb-0">Total Pengingat Aktif</h5>
                <h1 class="card-text-custom" id="statTotalCount">0</h1>
            </div>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
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

    {{-- Table Section --}}
    <div class="table-container shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Nama Pembayaran</th>
                        <th>Tipe</th>
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
            <nav><ul class="pagination pagination-sm mb-0" id="paginationLinks"></ul></nav>
        </div>
    </div>
</div>

@include('pengingat.modals.tambah_pengingat')
@include('pengingat.modals.edit_pengingat')
@include('pengingat.modals.detail_pengingat')

@endsection

@push('scripts')
<script>
    // Konfigurasi Global
    let currentFrekuensi = 'semua';
    let searchQuery = '';
    let currentPage = 1;
    const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

    // 1. Fungsi Utama: Load Data
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

    // 2. Fungsi Helper: Load Dropdown Modal
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

    // 3. Fungsi Render UI
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
                <td><span class="badge-custom badge-expense">${item.tipe}</span></td>
                <td><span class="text-muted"><i class="bi bi-clock-history me-1"></i> ${item.frekuensi}</span></td>
                <td class="fw-bold text-danger">${formatter.format(item.jumlah)}</td>
                <td class="text-end pe-4">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn-action" onclick="detailPengingat(${item.id_pengingat})" title="Detail">
                            <iconify-icon icon="ic:round-remove-red-eye" class="text-primary"></iconify-icon>
                        </button>
                        <button class="btn-action" onclick="editPengingat(${item.id_pengingat})" title="Edit">
                            <iconify-icon icon="ic:round-edit" class="text-warning"></iconify-icon>
                        </button>
                        <form action="/pembayaran-reguler/${item.id_pengingat}" method="POST" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-action"><iconify-icon icon="ic:round-delete" class="text-danger"></iconify-icon></button>
                        </form>
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

                // Load select box dengan data terpilih
                loadRekeningToModal('editRekening', data.id_rekening);
                loadKategoriPengeluaranOnly('editKategori', data.id_kategori);

                new bootstrap.Modal(document.getElementById('editPengingatModal')).show();
            });
    };

    // Handle Submit Form Edit (Letakkan di dalam DOMContentLoaded)
    // Handle Form Edit
    const editForm = document.getElementById('editPengingatForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validasi Sederhana
            const requiredFields = editForm.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value || field.value === "") {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                alert('Harap lengkapi semua bidang yang wajib diisi!');
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
                    alert('Data berhasil diperbarui!');
                } else {
                    alert('Gagal memperbarui: ' + (data.message || 'Periksa kembali data Anda.'));
                }
            });
        });
    }

    // 4. Inisialisasi Event Listeners (Digabung Jadi Satu)
    document.addEventListener('DOMContentLoaded', function() {
        loadPengingat(1);

        // Modal Logic
        const addModal = document.getElementById('addPengingatModal');
        if (addModal) {
            addModal.addEventListener('show.bs.modal', () => {
                loadRekeningToModal('modalRekening');
                loadKategoriPengeluaranOnly('modalKategori');
            });
        }

        // Form Submission Logic
        const addForm = document.getElementById('addPengingatForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Validasi Sederhana
                const requiredFields = addForm.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value || field.value === "") {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    alert('Harap lengkapi semua bidang yang wajib diisi!');
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
                        alert('Berhasil disimpan!');
                    } else {
                        // Menampilkan error validasi dari server jika ada
                        alert('Gagal: ' + (data.message || 'Harap periksa kembali inputan Anda.'));
                    }
                });
            });
        }

        // Tabs & Search Logic
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
    });
</script>
@endpush