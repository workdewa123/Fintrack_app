@extends('layout.app')

@section('content')
<style>
    /* Konsistensi dengan Kategori & Rekening Page */
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .transaksi-header {
        background-color: #4a90e2;
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 1rem;
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

    .table thead th {
    background-color: #f1f5f9;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
        padding: 1rem;
        border: none;
    }
    .table thead tr {
    background-color: #f1f5f9; /* Pindahkan warna background ke baris (tr) */
}

    .table tbody td {
        vertical-align: middle;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Badge Styles */
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

    /* Action Buttons */
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
        background-color: #3b82f6;
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

    /* Stat Cards Specific */
    .card-stat-mini {
        border: none;
        border-radius: 1rem;
        background: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    /* Update/Tambahkan di bagian <style> index.blade.php */
    .total-rekening {
        background-color: #3b82f6;
        color: white;
        border-radius: 1.5rem;
    }

    .total-pemasukan {
        background-color: #e8f9ef;
        color: #333;
        border-radius: 1.5rem;
    }

    .total-pengeluaran {
        background-color: #fdeaea;
        color: #333;
        border-radius: 1.5rem;
    }

    .card-text-custom {
        font-size: clamp(1.2rem, 3vw, 2rem) !important;
        word-wrap: break-word;
        font-weight: 700;
        line-height: 1.2;
        margin: 0.5rem 0;
    }
</style>

<div class="container-fluid py-4 px-md-4">
    <div class="mb-4">
        <h3 class="fw-bold">Riwayat Transaksi</h3>
        <p class="text-muted">Kelola dan pantau detail pengeluaran & pemasukan Anda</p>
    </div>

    {{-- Header Section (Statistics) - Updated to Match Beranda --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card total-rekening p-4 h-100">
                <h6 class="text-white-50">Saldo Keseluruhan</h6>
                <h2 class="card-text-custom text-white" id="statTotal">Rp 0</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card total-pemasukan p-4 h-100">
                <div class="card-body p-0">
                    <h6 class="text-muted">Total Pemasukan</h6>
                    <h2 class="card-text-custom text-success" id="statMasuk">Rp 0</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card total-pengeluaran p-4 h-100">
                <div class="card-body p-0">
                    <h6 class="text-muted">Total Pengeluaran</h6>
                    <h2 class="card-text-custom text-danger" id="statKeluar">Rp 0</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Action Bar --}}
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
            <div class="d-flex gap-2 justify-content-md-end">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border" style="max-width: 300px;">
                    <span class="input-group-text bg-white border-0 ps-3">
                        <iconify-icon icon="ic:round-search" class="text-muted"></iconify-icon>
                    </span>
                    <input type="text" id="txSearch" class="form-control border-0 py-2 shadow-none" placeholder="Cari transaksi...">
                </div>
                <a href="{{ route('laporan.pdf') }}" class="btn btn-outline-danger rounded-pill px-3 d-flex align-items-center gap-2">
                    <iconify-icon icon="mingcute:pdf-fill"></iconify-icon> PDF
                </a>
                <button class="btn btn-primary-custom text-white d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
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
                        <th class="ps-4">Tanggal</th>
                        <th>Kategori & Catatan</th>
                        <th>Rekening</th>
                        <th>Jumlah</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="txTableBody">
                    {{-- Diisi oleh JavaScript --}}
                </tbody>
            </table>
            <div id="emptyState" class="text-center py-5" style="display: none;">
                <iconify-icon icon="line-md:coffee-loop" style="font-size: 3rem;" class="text-muted mb-2"></iconify-icon>
                <p class="text-muted mb-0">Tidak ada transaksi yang ditemukan.</p>
            </div>
        </div>
        
        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center p-4 border-top bg-light">
            <div id="txPaginationInfo" class="text-muted small fw-medium">
                Memuat data...
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="txPaginationLinks"></ul>
            </nav>
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