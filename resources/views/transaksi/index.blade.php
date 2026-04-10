@extends('layout.app')

@section('content')
<style>
    /* Konsistensi Header Cards */
    .card-stat { border-radius: 1.25rem; border: none; transition: transform 0.2s; min-height: 120px; }
    .bg-total { background: #4a90e2; color: white; }
    .bg-masuk { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .bg-keluar { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* Filter Underline */
    .tabs-container .nav { border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; }
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

    .table-container { background: white; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; }
</style>

<div class="container-fluid py-4 px-md-4">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Riwayat Transaksi</h3>
            <p class="text-muted small">Kelola dan pantau detail pengeluaran & pemasukan Anda</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.pdf') }}" class="btn btn-outline-danger rounded-pill px-4 btn-sm d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
            </a>
            <button class="btn btn-primary rounded-pill px-4 btn-sm" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                + Baru
            </button>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-stat bg-total p-3 shadow-sm d-flex justify-content-center">
                <div class="card-body py-2">
                    <small class="opacity-75 d-block mb-1">Total Volume</small>
                    <h3 class="fw-bold mb-0" id="statTotal">Rp 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat bg-masuk p-3 shadow-sm d-flex justify-content-center">
                <div class="card-body py-2">
                    <small class="d-block mb-1">Total Pemasukan</small>
                    <h3 class="fw-bold mb-0" id="statMasuk">Rp 0</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stat bg-keluar p-3 shadow-sm d-flex justify-content-center">
                <div class="card-body py-2">
                    <small class="d-block mb-1">Total Pengeluaran</small>
                    <h3 class="fw-bold mb-0" id="statKeluar">Rp 0</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="row mb-4 align-items-center g-3">
        <div class="col-md-6">
            <div class="tabs-container">
                <nav class="nav">
                    <a class="nav-link active" data-tipe="semua">Semua</a>
                    <a class="nav-link" data-tipe="MASUK">Pemasukan</a>
                    <a class="nav-link" data-tipe="KELUAR">Pengeluaran</a>
                </nav>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white">
                <span class="input-group-text bg-white border-0 ps-3"><i class="bi bi-search"></i></span>
                <input type="text" id="txSearch" class="form-control border-0 py-2 shadow-none" placeholder="Cari catatan atau kategori...">
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-container shadow-sm border">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4" style="font-size: 0.85rem;">TANGGAL</th>
                        <th style="font-size: 0.85rem;">KATEGORI</th>
                        <th style="font-size: 0.85rem;">TIPE</th>
                        <th style="font-size: 0.85rem;">REKENING</th>
                        <th style="font-size: 0.85rem;">JUMLAH</th>
                        <th class="text-end pe-4" style="font-size: 0.85rem;">AKSI</th>
                    </tr>
                </thead>
                <tbody id="txTableBody">
                    {{-- Data via AJAX --}}
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
            <small id="txPaginationInfo" class="text-muted"></small>
            <nav><ul class="pagination pagination-sm mb-0" id="txPaginationLinks"></ul></nav>
        </div>
    </div>
</div>

{{-- Modal harus di luar kontainer utama jika memungkinkan --}}
@include('transaksi.modals.tambah_transaksi')
@include('transaksi.modals.edit_transaksi')

@endsection

@push('scripts')
<script>
    let currentTipe = 'semua';
    let searchQuery = '';
    let currentPage = 1;

    const formatter = new Intl.NumberFormat('id-ID', { 
        style: 'currency', 
        currency: 'IDR', 
        minimumFractionDigits: 0 
    });

    // --- 1. FUNGSI LOAD DATA ---
    function loadTx(page = 1) {
        currentPage = page;
        fetch(`/transaksi-data?page=${page}&tipe=${currentTipe}&search=${searchQuery}`)
            .then(res => res.json())
            .then(data => {
                // Update Statistik Cards
                document.getElementById('statTotal').innerText = formatter.format(data.total_volume || 0);
                document.getElementById('statMasuk').innerText = formatter.format(data.total_pemasukan || 0);
                document.getElementById('statKeluar').innerText = formatter.format(data.total_pengeluaran || 0);
                
                renderTable(data.data);
                renderPagination(data);
            })
            .catch(err => console.error("Error loading data:", err));
    }

    // --- 2. FUNGSI RENDER TABEL ---
    function renderTable(items) {
        const body = document.getElementById('txTableBody');
        if(!items || items.length === 0) {
            body.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Data tidak ditemukan</td></tr>';
            return;
        }

        body.innerHTML = items.map(item => `
            <tr>
                <td class="ps-4 text-muted">${new Date(item.tanggal_transaksi).toLocaleDateString('id-ID')}</td>
                <td>
                    <div class="fw-bold text-dark">${item.kategori?.nama_kategori || 'Umum'}</div>
                    <small class="text-muted">${item.keterangan || '-'}</small> 
                </td>
                <td>
                    <span class="badge ${item.tipe === 'MASUK' ? 'bg-success' : 'bg-danger'} bg-opacity-10 ${item.tipe === 'MASUK' ? 'text-success' : 'text-danger'} px-3" style="font-size: 0.75rem;">
                        ${item.tipe}
                    </span>
                </td>
                <td>
                    <span class="text-secondary small">
                        <i class="bi bi-wallet2 me-1"></i>${item.rekening?.nama_rekening || '-'}
                    </span>
                </td>
                <td class="fw-bold ${item.tipe === 'MASUK' ? 'text-success' : 'text-danger'}">
                    ${item.tipe === 'MASUK' ? '+' : '-'} ${formatter.format(item.jumlah)}
                </td>
                <td class="text-end pe-4">
                    <div class="d-flex justify-content-end gap-1">
                        <button class="btn btn-sm btn-light border rounded-pill me-1" onclick="editTransaksi(${item.id_transaksi})">
                            <i class="bi bi-pencil text-primary"></i>
                        </button>
                        <button class="btn btn-sm btn-light border rounded-pill text-danger"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    window.editTransaksi = function(id) {
        fetch(`/transaksi/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('editTransaksiId').value = data.id_transaksi;
                document.getElementById('editJumlah').value = data.jumlah;
                document.getElementById('editCatatan').value = data.keterangan || '';
                
                // FORMAT TANGGAL YANG BENAR (YYYY-MM-DDTHH:MM)
                if (data.tanggal_transaksi) {
                    const datePart = data.tanggal_transaksi.replace(' ', 'T').substring(0, 16);
                    document.getElementById('editTanggalWaktu').value = datePart;
                }
                
                // Sinkronisasi Tipe
                document.getElementById('editJenisTransaksi').value = data.tipe;
                document.getElementById('editJenisTransaksiHidden').value = data.tipe;

                // Memanggil fungsi load dengan target elemen modal edit
                loadKategoriToModal('editKategori', data.id_kategori);
                loadRekeningToModal('editRekening', data.id_rekening);

                new bootstrap.Modal(document.getElementById('editTransactionModal')).show();
            });
    };

    // --- TAMBAHKAN INI: Auto-detect Tipe untuk Modal Edit ---
    const editKategoriSelect = document.getElementById('editKategori');
    if (editKategoriSelect) {
        editKategoriSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const tipe = selectedOption.getAttribute('data-tipe');

            const jenisVisual = document.getElementById('editJenisTransaksi');
            const jenisHidden = document.getElementById('editJenisTransaksiHidden');

            if (tipe) {
                jenisVisual.value = tipe; 
                jenisHidden.value = tipe;
            }
        });
    }

    // Update listener submit di DOMContentLoaded
    const editForm = document.getElementById('editTransactionForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('editTransaksiId').value;
            const formData = {
                id_rekening: document.getElementById('editRekening').value,
                id_kategori: document.getElementById('editKategori').value,
                tipe: document.getElementById('editJenisTransaksiHidden').value,
                jumlah: document.getElementById('editJumlah').value,
                tanggal_transaksi: document.getElementById('editTanggalWaktu').value,
                keterangan: document.getElementById('editCatatan').value,
                _method: 'PUT' // Penting untuk rute resource Laravel
            };

            fetch(`/transaksi/${id}`, {
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
                bootstrap.Modal.getInstance(document.getElementById('editTransactionModal')).hide();
                alert('Transaksi berhasil diperbarui!');
                loadTx(currentPage);
            });
        });
    }

    // --- 3. FUNGSI PAGINASI ---
    function renderPagination(data) {
        const info = document.getElementById('txPaginationInfo');
        const links = document.getElementById('txPaginationLinks');
        if(!info || !links) return;

        info.innerText = `Menampilkan ${data.from || 0} - ${data.to || 0} dari ${data.total || 0} data`;
        
        links.innerHTML = (data.links || []).map(link => {
            let pageNum = null;
            if (link.url) {
                const urlParams = new URLSearchParams(link.url.split('?')[1]);
                pageNum = urlParams.get('page');
            }

            const isActive = link.active ? 'active' : '';
            const isDisabled = !link.url ? 'disabled' : '';
            const label = link.label.replace('&laquo; Previous', '‹').replace('Next &raquo;', '›');

            return `
                <li class="page-item ${isActive} ${isDisabled}">
                    <a class="page-link shadow-none" href="#" onclick="event.preventDefault(); if(${pageNum}) loadTx(${pageNum})">
                        ${label}
                    </a>
                </li>
            `;
        }).join('');
    }

    // --- 4. INISIALISASI SAAT HALAMAN DIMUAT ---
    document.addEventListener('DOMContentLoaded', function() {
        loadTx(1);
        loadRekeningToModal();
        loadKategoriToModal();

        // Listener Filter Tipe (Semua, Masuk, Keluar)
        document.querySelectorAll('.tabs-container .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.tabs-container .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                currentTipe = this.dataset.tipe;
                loadTx(1);
            });
        });

        // Auto-detect Tipe dari Kategori
        const kategoriSelect = document.getElementById('kategori');
        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const tipe = selectedOption.getAttribute('data-tipe');
                const jenisVisual = document.getElementById('jenisTransaksi');
                const jenisHidden = document.getElementById('jenisTransaksiHidden');

                if (tipe) {
                    jenisVisual.value = tipe; 
                    jenisHidden.value = tipe;
                }
            });
        }

        // Search dengan Debounce
        let searchTimer;
        const searchInput = document.getElementById('txSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimer);
                searchQuery = e.target.value;
                searchTimer = setTimeout(() => { loadTx(1); }, 500);
            });
        }

        // Submit Form Tambah Transaksi
        const addForm = document.getElementById('addTransactionForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = {
                    id_rekening: document.getElementById('rekening').value,
                    id_kategori: document.getElementById('kategori').value,
                    tipe: document.getElementById('jenisTransaksiHidden').value,
                    jumlah: document.getElementById('jumlah').value,
                    tanggal_transaksi: document.getElementById('tanggalWaktu').value,
                    catatan: document.getElementById('catatan').value,
                };

                fetch('/api/transaksi-simpan', {
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
                        const modalElem = document.getElementById('addTransactionModal');
                        bootstrap.Modal.getInstance(modalElem).hide();
                        addForm.reset();
                        alert('Transaksi berhasil disimpan!');
                        loadTx(1);
                    } else {
                        alert('Gagal: ' + data.error);
                    }
                })
                .catch(err => console.error(err));
            });
        }
    });

    // Ganti bagian fetch di index.blade.php Anda:

    function loadRekeningToModal(targetId = 'rekening', selectedValue = null) {
        fetch('/api/rekening-all') // Ubah URL ke API baru
            .then(res => res.json())
            .then(data => { // Response sekarang langsung berupa array, bukan object pagination
                const select = document.getElementById(targetId);
                if (select) {
                    select.innerHTML = '<option disabled selected>Pilih Rekening</option>';
                    data.forEach(r => {
                        const isSelected = r.id_rekening == selectedValue ? 'selected' : '';
                        select.innerHTML += `<option value="${r.id_rekening}" ${isSelected}>${r.nama_rekening} (Rp ${new Intl.NumberFormat('id-ID').format(r.saldo)})</option>`;
                    });
                }
            });
    }

    function loadKategoriToModal(targetId = 'kategori', selectedValue = null) {
        fetch('/api/kategori-all') // Ubah URL ke API baru
            .then(res => res.json())
            .then(data => { // Response sekarang langsung berupa array
                const select = document.getElementById(targetId);
                if (select) {
                    select.innerHTML = '<option disabled selected>Pilih Kategori</option>';
                    data.forEach(k => {
                        const isSelected = k.id_kategori == selectedValue ? 'selected' : '';
                        select.innerHTML += `<option value="${k.id_kategori}" data-tipe="${k.tipe}" ${isSelected}>${k.nama_kategori} (${k.tipe})</option>`;
                    });
                }
            });
    }
</script>
@endpush