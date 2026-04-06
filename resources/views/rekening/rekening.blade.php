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

    .rekening-list .list-group-item {
        border-top: none;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
    }

    .rekening-list .list-group-item:first-child {
        border-top: none;
    }

    .rekening-list .list-group-item:last-child {
        border-bottom: none;
    }

    .account-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        margin-right: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .account-actions .action-link {
        color: #94a3b8;
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        margin-right: 12px;
    }

    .action-link.edit {
        color: #22c55e;
    }

    .action-link.delete {
        color: #ef4444;
    }

    .action-link.history {
        color: #3b82f6;
    }

    .action-link i {
        margin-right: 4px;
    }

    .icon-item,
    .color-item {
        transition: all 0.2s ease-in-out;
    }

    .icon-item {
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        border: 1px solid #d1d5db;
        background-color: #ffffff;
        color: #6b7280;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .icon-item.active {
        background-color: #e0e7ff;
        border-color: #3b82f6;
        color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
    }

    .color-item {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
    }

    .color-item.active {
        border-color: #4a5568;
        box-shadow: 0 0 0 3px rgba(74, 85, 104, 0.2);
    }

    .icon-in-circle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 1.25rem;
    }

    .rekening-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    <div class="card shadow-sm mb-4 rounded-4">
        <div class="card-body">
            <div class="list-group list-group-flush rekening-list" id="rekeningList">
                <p class="text-muted text-center py-5" id="emptyState">Belum ada rekening yang ditambahkan.</p>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH REKENING --}}
@include('rekening.modals.tambah_rekening')

{{-- MODAL EDIT REKENING --}}
@include('rekening.modals.edit_rekening')

{{-- MODAL HAPUS REKENING --}}
@include('rekening.modals.hapus_rekening')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elemen-elemen DOM yang dibutuhkan
        const rekeningList = document.getElementById('rekeningList');
        const emptyState = document.getElementById('emptyState');
        const totalSaldoEl = document.getElementById('totalSaldo');
        const searchInput = document.getElementById('searchInput');

        // Modal Tambah Rekening
        const tambahRekeningForm = document.getElementById('tambahRekeningForm');
        const tambahRekeningModalEl = document.getElementById('tambahRekeningModal');
        const tambahRekeningModal = new bootstrap.Modal(tambahRekeningModalEl);

        // Modal Edit Rekening
        const editRekeningModalEl = document.getElementById('editRekeningModal');
        const editRekeningModal = new bootstrap.Modal(editRekeningModalEl);
        const editRekeningForm = document.getElementById('editRekeningForm');

        // Modal Hapus Rekening
        const hapusRekeningModalEl = document.getElementById('hapusRekeningModal');
        const hapusRekeningModal = new bootstrap.Modal(hapusRekeningModalEl);
        const formHapusRekening = document.getElementById('formHapusRekening');

        // Variabel untuk menyimpan data rekening asli
        let allRekeningData = [];

        // Variabel untuk menyimpan ID rekening yang sedang diedit
        let currentRekeningId = null;

        // Fungsi untuk memuat data rekening dari server
        function loadRekeningFromDatabase() {
            fetch('/rekening-data')
                .then(response => response.json())
                .then(data => {
                    allRekeningData = data; // Simpan data asli
                    renderRekeningList(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data rekening. Silakan refresh halaman.');
                });
        }

        // Fungsi untuk render daftar rekening
        function renderRekeningList(data) {
            rekeningList.innerHTML = '';
            let totalSaldo = 0;

            if (data.length === 0) {
                emptyState.style.display = 'block';
                emptyState.textContent = 'Tidak ada rekening yang ditemukan.';
            } else {
                emptyState.style.display = 'none';
                data.forEach(rekening => {
                    const newRekeningItem = createRekeningItem(rekening);
                    rekeningList.appendChild(newRekeningItem);
                    if (rekening.mata_uang === 'IDR') {
                        totalSaldo += parseFloat(rekening.saldo);
                    }
                });
            }
            totalSaldoEl.textContent = `Rp. ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(totalSaldo)}`;
        }

        // Panggil fungsi untuk pertama kali memuat data
        loadRekeningFromDatabase();

        // Fungsi untuk membuat elemen rekening
        function createRekeningItem(data) {
            const newRekeningItem = document.createElement('div');
            newRekeningItem.classList.add('list-group-item', 'd-flex', 'align-items-center', 'justify-content-between');
            newRekeningItem.setAttribute('data-rekening-id', data.id_rekening);
            newRekeningItem.id = `rekening-${data.id_rekening}`;

            newRekeningItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="account-icon me-3" style="background-color: ${data.warna};">
                        <i class="bi ${data.icon}"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">${data.nama_rekening}</h6>
                        <small class="text-muted">${data.mata_uang}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <div class="text-end">
                        <h6 class="mb-0 fw-bold">${data.mata_uang === 'IDR' ? 'Rp.' : data.mata_uang} ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(data.saldo)}</h6>
                    </div>
                    <div class="account-actions d-flex gap-2 align-items-center">
                        <a href="#" class="action-link edit text-success"
                           data-id="${data.id_rekening}"
                           data-nama="${data.nama_rekening}"
                           data-saldo="${data.saldo}"
                           data-matauang="${data.mata_uang}"
                           data-icon="${data.icon}"
                           data-warna="${data.warna}">
                            <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Edit</span>
                        </a>
                        <a href="#" class="action-link delete text-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#hapusRekeningModal" 
                        data-id="${data.id_rekening}" 
                        data-name="${data.nama_rekening}">
                            <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                        </a>
                        <a href="{{ route('riwayat.transfer') }}?rekening_id=${data.id_rekening}" class="action-link history text-primary">
                            <i class="bi bi-clock-history"></i> <span class="d-none d-md-inline">Lihat Riwayat</span>
                        </a>
                    </div>
                </div>
            `;
            return newRekeningItem;
        }

        // Fungsi untuk mencari rekening berdasarkan nama
        function searchRekening(keyword) {
            if (!keyword.trim()) {
                // Jika pencarian kosong, tampilkan semua data
                renderRekeningList(allRekeningData);
                return;
            }

            const filteredData = allRekeningData.filter(rekening =>
                rekening.nama_rekening.toLowerCase().includes(keyword.toLowerCase())
            );

            renderRekeningList(filteredData);
        }

        // Event listener untuk input pencarian
        searchInput.addEventListener('input', function() {
            searchRekening(this.value);
        });

        // Fungsi untuk menangani klik pada tombol ikon dan warna di modal
        function setupModalPickers(modalId, iconInputId, colorInputId) {
            const modal = document.getElementById(modalId);
            const iconGrid = modal.querySelector('.icon-grid');
            const colorPicker = modal.querySelector('.color-picker');
            const selectedIconInput = document.getElementById(iconInputId);
            const selectedColorInput = document.getElementById(colorInputId);

            iconGrid.querySelectorAll('.icon-item').forEach(item => {
                item.addEventListener('click', function() {
                    iconGrid.querySelectorAll('.icon-item').forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    selectedIconInput.value = this.dataset.icon;
                });
            });

            colorPicker.querySelectorAll('.color-item').forEach(item => {
                item.addEventListener('click', function() {
                    colorPicker.querySelectorAll('.color-item').forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    selectedColorInput.value = this.dataset.color;
                });
            });
        }

        // Panggil fungsi setup untuk kedua modal
        setupModalPickers('tambahRekeningModal', 'selectedIcon', 'selectedColor');
        setupModalPickers('editRekeningModal', 'editSelectedIcon', 'editSelectedColor');

        // Logika untuk mengisi modal edit saat tombol "Edit" diklik
        rekeningList.addEventListener('click', function(e) {
            const editButton = e.target.closest('.action-link.edit');
            if (editButton) {
                e.preventDefault();

                // Ambil data dari atribut data-* tombol dan simpan ke variabel global
                currentRekeningId = editButton.dataset.id;
                const namaRekening = editButton.dataset.nama;
                const saldo = editButton.dataset.saldo;
                const mataUang = editButton.dataset.matauang;
                const icon = editButton.dataset.icon;
                const warna = editButton.dataset.warna;

                // Isi form modal edit dengan data yang diambil
                document.getElementById('editNamaRekening').value = namaRekening;
                document.getElementById('editSaldoAwal').value = saldo;
                document.getElementById('editMataUang').value = mataUang;
                document.getElementById('editSelectedIcon').value = icon;
                document.getElementById('editSelectedColor').value = warna;

                // Atur kelas 'active' pada ikon dan warna yang sesuai
                const editIconGrid = editRekeningModalEl.querySelector('.icon-grid');
                const editColorPicker = editRekeningModalEl.querySelector('.color-picker');

                editIconGrid.querySelectorAll('.icon-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.dataset.icon === icon) {
                        item.classList.add('active');
                    }
                });

                editColorPicker.querySelectorAll('.color-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.dataset.color === warna) {
                        item.classList.add('active');
                    }
                });

                // Tampilkan modal
                editRekeningModal.show();
            }
        });

        // Logika untuk submit form tambah rekening
        tambahRekeningForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const namaRekening = document.getElementById('namaRekening').value;
            const saldoAwal = parseFloat(document.getElementById('saldoAwal').value) || 0;
            const mataUang = document.getElementById('mataUang').value;
            const icon = document.getElementById('selectedIcon').value;
            const warna = document.getElementById('selectedColor').value;

            if (!icon || !warna) {
                alert('Silakan pilih ikon dan warna untuk rekening.');
                return;
            }

            const formData = {
                _token: '{{ csrf_token() }}',
                nama_rekening: namaRekening,
                saldo: saldoAwal,
                mata_uang: mataUang,
                icon: icon,
                warna: warna,
            };

            fetch('/rekening', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Terjadi kesalahan saat menambahkan rekening.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Rekening berhasil ditambahkan!');
                    tambahRekeningModal.hide();
                    tambahRekeningForm.reset();
                    // Reset ikon dan warna ke default setelah berhasil
                    const tambahIconGrid = tambahRekeningModalEl.querySelector('.icon-grid');
                    const tambahColorPicker = tambahRekeningModalEl.querySelector('.color-picker');
                    tambahIconGrid.querySelectorAll('.icon-item').forEach(el => el.classList.remove('active'));
                    tambahColorPicker.querySelectorAll('.color-item').forEach(el => el.classList.remove('active'));
                    tambahIconGrid.querySelector('[data-icon="bi-wallet2"]').classList.add('active');
                    tambahColorPicker.querySelector('[data-color="#3b82f6"]').classList.add('active');
                    document.getElementById('selectedIcon').value = 'bi-wallet2';
                    document.getElementById('selectedColor').value = '#3b82f6';

                    // Reload data dan reset pencarian
                    loadRekeningFromDatabase();
                    searchInput.value = '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
        });

        // Logika untuk submit form edit rekening
        editRekeningForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Ambil ID dari variabel global
            const rekeningId = currentRekeningId;
            if (!rekeningId) {
                alert('Terjadi kesalahan: ID rekening tidak ditemukan.');
                return;
            }

            const updatedNamaRekening = document.getElementById('editNamaRekening').value;
            const updatedSaldo = parseFloat(document.getElementById('editSaldoAwal').value) || 0;
            const updatedMataUang = document.getElementById('editMataUang').value;
            const updatedIcon = document.getElementById('editSelectedIcon').value;
            const updatedWarna = document.getElementById('editSelectedColor').value;

            if (!updatedIcon || !updatedWarna) {
                alert('Silakan pilih ikon dan warna untuk rekening.');
                return;
            }

            const formData = {
                _token: '{{ csrf_token() }}',
                nama_rekening: updatedNamaRekening,
                saldo: updatedSaldo,
                mata_uang: updatedMataUang,
                icon: updatedIcon,
                warna: updatedWarna,
            };

            fetch(`/rekening/${rekeningId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Terjadi kesalahan saat mengupdate rekening.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Rekening berhasil diupdate!');
                    editRekeningModal.hide();

                    // Reload data dan pertahankan pencarian jika ada
                    loadRekeningFromDatabase();
                    if (searchInput.value.trim()) {
                        searchRekening(searchInput.value);
                    }
                })
                .catch(error => {
                    console.error('Error updating rekening:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
        });

        // Logika untuk hapus rekening
        formHapusRekening.addEventListener('submit', function(e) {
            e.preventDefault();

            const rekeningId = document.getElementById('hapusRekeningIdInput').value;
            const btnHapusRekening = document.getElementById('btnHapusRekening');

            if (!rekeningId) {
                alert('ID rekening tidak ditemukan. Gagal menghapus.');
                return;
            }

            // Tampilkan loading state
            btnHapusRekening.disabled = true;
            btnHapusRekening.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...';

            fetch(`/rekening/${rekeningId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Gagal menghapus rekening.');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Hapus elemen rekening dari DOM
                    const rekeningElement = document.getElementById(`rekening-${rekeningId}`);
                    if (rekeningElement) {
                        rekeningElement.remove();
                    }

                    // Periksa apakah masih ada rekening yang tersisa
                    const remainingRekening = document.querySelectorAll('[data-rekening-id]');
                    if (remainingRekening.length === 0) {
                        emptyState.style.display = 'block';
                        emptyState.textContent = 'Tidak ada rekening yang ditemukan.';
                    }

                    // Hitung ulang total saldo
                    updateTotalSaldo();

                    // Tutup modal
                    hapusRekeningModal.hide();

                    // Tampilkan pesan sukses
                    alert('Rekening berhasil dihapus.');

                    // Perbarui data asli
                    loadRekeningFromDatabase();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus rekening: ' + error.message);
                })
                .finally(() => {
                    // Reset button state
                    btnHapusRekening.disabled = false;
                    btnHapusRekening.innerHTML = 'Hapus';
                });
        });

        // Fungsi untuk menghitung ulang total saldo
        function updateTotalSaldo() {
            let totalSaldo = 0;
            const rekeningItems = document.querySelectorAll('[data-rekening-id]');

            rekeningItems.forEach(item => {
                const saldoText = item.querySelector('.text-end h6').textContent;
                if (saldoText.includes('Rp.')) {
                    const saldoValue = parseFloat(saldoText.replace('Rp.', '').replace(/\./g, ''));
                    totalSaldo += saldoValue;
                }
            });

            totalSaldoEl.textContent = `Rp. ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(totalSaldo)}`;
        }
    });
</script>
@endpush