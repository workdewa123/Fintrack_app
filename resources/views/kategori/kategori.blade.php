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
            <li class="nav-item"><a class="nav-link active" data-type="semua">Semua</a></li>
            <li class="nav-item"><a class="nav-link" data-type="MASUK">Pemasukan</a></li>
            <li class="nav-item"><a class="nav-link" data-type="KELUAR">Pengeluaran</a></li>
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
                <tbody id="kategoriTableBody">
                    {{-- Kosong: Akan diisi oleh JavaScript --}}
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light">
            <div id="paginationInfo" class="text-muted small">Memuat data...</div>
            <nav>
                <ul class="pagination pagination-sm mb-0" id="paginationLinks"></ul>
            </nav>
        </div>
    </div>
</div>

{{-- Modal diletakkan di luar loop karena data di-render via JS --}}
@include('kategori.modals.tambah_kategori')
@include('kategori.modals.edit_kategori')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@push('scripts')
<script>
    let currentPage = 1;
    let currentFilter = 'semua';

    document.addEventListener('DOMContentLoaded', function() {
    loadKategoriData();

    // PERBAIKAN: Gunakan selector yang lebih spesifik (hanya di dalam .tabs-container)
    const filterTabs = document.querySelectorAll('.tabs-container .nav-link');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault(); 
            
            // Hapus class active dari sesama filter tab saja
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            currentFilter = this.getAttribute('data-type') || 'semua';
            loadKategoriData(1);
        });
    });
});

    function loadKategoriData(page = 1) {
        currentPage = page;
        // Gunakan path yang sesuai dengan route prefix di web.php
        fetch(`/kategori/kategori-data?page=${page}&tipe=${currentFilter}`)
            .then(response => response.json())
            .then(data => {
                renderKategoriTable(data.data);
                renderPagination(data);
            })
            .catch(err => {
                console.error('Error:', err);
                document.getElementById('kategoriTableBody').innerHTML = '<tr><td colspan="3" class="text-center text-danger">Gagal memuat data.</td></tr>';
            });
    }

    function renderKategoriTable(categories) {
        const tbody = document.getElementById('kategoriTableBody');
        tbody.innerHTML = '';

        if (categories.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-muted">Belum ada data kategori.</td></tr>';
            return;
        }

        categories.forEach(kat => {
            const isMasuk = kat.tipe === 'MASUK';
            const badgeClass = isMasuk ? 'badge-income' : 'badge-expense';
            const tipeText = isMasuk ? 'Pemasukan' : 'Pengeluaran';
            
            tbody.innerHTML += `
                <tr>
                    <td class="ps-3 fw-medium text-dark">${kat.nama_kategori}</td>
                    <td>
                        <span class="badge ${badgeClass} px-3 py-2 rounded-2">${tipeText}</span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-sm btn-light border shadow-sm" onclick="editKategori(${kat.id_kategori})">
                                <iconify-icon icon="ic:round-edit" class="text-primary"></iconify-icon>
                            </button>
                            <form action="/kategori/destroy/${kat.id_kategori}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border shadow-sm">
                                    <iconify-icon icon="ic:round-delete" class="text-danger"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    function renderPagination(data) {
        const linksContainer = document.getElementById('paginationLinks');
        const info = document.getElementById('paginationInfo');
        
        linksContainer.innerHTML = '';
        info.textContent = `Menampilkan ${data.from || 0} - ${data.to || 0} dari ${data.total} kategori`;

        // Tombol Previous
        linksContainer.innerHTML += `
            <li class="page-item ${data.prev_page_url ? '' : 'disabled'}">
                <a class="page-link" href="#" onclick="changePage(event, ${data.current_page - 1})">‹</a>
            </li>
        `;

        // Tombol Angka
        data.links.forEach((link, idx) => {
            if (idx > 0 && idx < data.links.length - 1) {
                const pageNum = link.label;
                linksContainer.innerHTML += `
                    <li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(event, ${pageNum})">${pageNum}</a>
                    </li>
                `;
            }
        });

        // Tombol Next
        linksContainer.innerHTML += `
            <li class="page-item ${data.next_page_url ? '' : 'disabled'}">
                <a class="page-link" href="#" onclick="changePage(event, ${data.current_page + 1})">›</a>
            </li>
        `;
    }

    window.changePage = function(e, page) {
        e.preventDefault();
        if (page && page !== currentPage) loadKategoriData(page);
    };

    // Fungsi untuk mengambil data dan membuka modal
window.editKategori = function(id) {
    fetch(`/kategori/show/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Gagal mengambil data');
            return response.json();
        })
        .then(kat => {
            // Isi data ke form modal (pastikan ID elemen sesuai dengan edit_kategori.blade.php)
            document.getElementById('edit_id_kategori').value = kat.id_kategori;
            document.getElementById('edit_nama_kategori').value = kat.nama_kategori;
            
            // Sesuaikan value select (karena di DB MASUK/KELUAR, di form pemasukan/pengeluaran)
            const tipeValue = kat.tipe === 'MASUK' ? 'pemasukan' : 'pengeluaran';
            document.getElementById('edit_tipe_kategori').value = tipeValue;

            // Tampilkan modal
            const modalElem = document.getElementById('editKategoriModal');
            const modal = new bootstrap.Modal(modalElem);
            modal.show();
        })
        .catch(err => {
            console.error(err);
            alert('Data kategori tidak ditemukan');
        });
};

// Tambahkan Event Listener untuk submit form (masukkan ke dalam DOMContentLoaded)
document.addEventListener('DOMContentLoaded', function() {
    // ... kode load data yang sudah ada ...

    const editForm = document.getElementById('editKategoriForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_id_kategori').value;
            const formData = new FormData(this);

            fetch(`/kategori/update/${id}`, {
                method: 'POST', // Kita pakai POST karena FormData, tapi di header nanti ada _method PUT
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            // --- TAMBAHKAN / GANTI MULAI DARI SINI ---
            .then(res => {
                if (!res.ok) throw new Error('Gagal memperbarui data');
                return res.json();
            })
            .then(data => {
                const modalElem = document.getElementById('editKategoriModal');
                const modal = bootstrap.Modal.getInstance(modalElem);
                if (modal) modal.hide();
                
                alert('Kategori berhasil diperbarui!');
                loadKategoriData(currentPage); 
            })
            // --- SAMPAI DI SINI ---
            .catch(err => alert('Terjadi kesalahan saat menyimpan'));
        });
    }
});
</script>
@endpush
@endsection