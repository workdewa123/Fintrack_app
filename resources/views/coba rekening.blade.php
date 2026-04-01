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

    /* PERBAIKAN PENTING DI SINI */
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
                <input type="text" class="form-control border-start-0 rounded-end-pill" placeholder="Cari Rekening...">
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

{{-- MODAL HAPUS REKENING (OPSIONAL) --}}
@include('rekening.modals.hapus_rekening')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rekeningList = document.getElementById('rekeningList');
        const emptyState = document.getElementById('emptyState');
        const totalSaldoEl = document.getElementById('totalSaldo');
        const tambahRekeningForm = document.getElementById('tambahRekeningForm');
        const tambahRekeningModalEl = document.getElementById('tambahRekeningModal');
        const tambahRekeningModal = new bootstrap.Modal(tambahRekeningModalEl);

        const editRekeningModalEl = document.getElementById('editRekeningModal');
        const editRekeningModal = new bootstrap.Modal(editRekeningModalEl);
        const editRekeningForm = document.getElementById('editRekeningForm');
        const editRekeningId = document.getElementById('editRekeningId');
        const editNamaRekening = document.getElementById('editNamaRekening');
        const editSaldoAwal = document.getElementById('editSaldoAwal');
        const editMataUang = document.getElementById('editMataUang');
        const editSelectedIcon = document.getElementById('editSelectedIcon');
        const editSelectedColor = document.getElementById('editSelectedColor');
        const editIconGrid = document.getElementById('editIconGrid');
        const editColorPicker = document.getElementById('editColorPicker');
        const tambahIconGrid = document.getElementById('tambahRekeningModal').querySelector('.icon-grid');
        const tambahColorPicker = document.getElementById('tambahRekeningModal').querySelector('.color-picker');

        function createRekeningItem(data) {
            const newRekeningItem = document.createElement('div');
            newRekeningItem.classList.add('list-group-item', 'd-flex', 'align-items-center', 'justify-content-between');
            newRekeningItem.setAttribute('data-rekening-id', data.id);

            const leftContainer = document.createElement('div');
            leftContainer.classList.add('d-flex', 'align-items-center');

            const iconContainer = document.createElement('div');
            iconContainer.classList.add('account-icon', 'me-3');
            iconContainer.style.backgroundColor = data.warna;

            // Pastikan Anda sudah menambahkan elemen ikon di sini
            const iconElement = document.createElement('i');
            iconElement.classList.add('bi', data.icon);
            iconContainer.appendChild(iconElement);

            const textContainer = document.createElement('div');
            textContainer.innerHTML = `
                <h6 class="mb-0 fw-bold">${data.nama_rekening}</h6>
                <small class="text-muted">${data.mata_uang}</small>
            `;

            leftContainer.appendChild(iconContainer);
            leftContainer.appendChild(textContainer);

            const rightContainer = document.createElement('div');
            rightContainer.classList.add('d-flex', 'align-items-center', 'gap-4');

            const saldoContainer = document.createElement('div');
            saldoContainer.classList.add('text-end');
            saldoContainer.innerHTML = `
                <h6 class="mb-0 fw-bold">${data.mata_uang === 'IDR' ? 'Rp.' : data.mata_uang} ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(data.saldo)}</h6>
            `;

            const actionsContainer = document.createElement('div');
            actionsContainer.classList.add('account-actions', 'd-flex', 'gap-2', 'align-items-center');
            actionsContainer.innerHTML = `
                <a href="#" class="action-link edit text-success" data-bs-toggle="modal" data-bs-target="#editRekeningModal" data-id="${data.id}">
                    <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Edit</span>
                </a>
                <a href="#" class="action-link delete text-danger" data-bs-toggle="modal" data-bs-target="#hapusRekeningModal" data-id="${data.id}">
                    <i class="bi bi-trash"></i> <span class="d-none d-md-inline">Hapus</span>
                </a>
                <a href="#" class="action-link history text-primary">
                    <span class="d-none d-md-inline">Lihat Riwayat Transfer</span> <i class="bi bi-chevron-right"></i>
                </a>
            `;

            rightContainer.appendChild(saldoContainer);
            rightContainer.appendChild(actionsContainer);

            newRekeningItem.appendChild(leftContainer);
            newRekeningItem.appendChild(rightContainer);

            return newRekeningItem;
        }

        function loadRekeningFromDatabase() {
            fetch('/rekening-data')
                .then(response => response.json())
                .then(data => {
                    rekeningList.innerHTML = '';
                    let totalSaldo = 0;

                    if (data.length === 0) {
                        rekeningList.appendChild(emptyState);
                        emptyState.style.display = 'block';
                    } else {
                        emptyState.style.display = 'none';
                        data.forEach(rekening => {
                            const newRekeningItem = createRekeningItem(rekening);
                            rekeningList.appendChild(newRekeningItem);
                            if (rekening.mata_uang === 'IDR') {
                                totalSaldo += parseFloat(rekening.saldo);
                            }
                        });
                        totalSaldoEl.textContent = `Rp. ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(totalSaldo)}`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data rekening. Silakan refresh halaman.');
                });
        }

        loadRekeningFromDatabase();

        function setupModalListeners(modalEl, selectedIconId, selectedColorId) {
            const selectedIconInput = document.getElementById(selectedIconId);
            const selectedColorInput = document.getElementById(selectedColorId);
            const iconGrid = modalEl.querySelector('.icon-grid');
            const colorPicker = modalEl.querySelector('.color-picker');

            if (modalEl) {
                modalEl.addEventListener('click', function(e) {
                    const clickedIcon = e.target.closest('.icon-item');
                    if (clickedIcon) {
                        iconGrid.querySelectorAll('.icon-item').forEach(el => el.classList.remove('active'));
                        clickedIcon.classList.add('active');
                        selectedIconInput.value = clickedIcon.dataset.icon;
                    }
                    const clickedColor = e.target.closest('.color-item');
                    if (clickedColor) {
                        colorPicker.querySelectorAll('.color-item').forEach(el => el.classList.remove('active'));
                        clickedColor.classList.add('active');
                        selectedColorInput.value = clickedColor.dataset.color;
                    }
                });
            }
        }

        setupModalListeners(tambahRekeningModalEl, 'selectedIcon', 'selectedColor');
        setupModalListeners(editRekeningModalEl, 'editSelectedIcon', 'editSelectedColor');

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
                    const tambahIconGrid = document.getElementById('tambahRekeningModal').querySelector('.icon-grid');
                    const tambahColorPicker = document.getElementById('tambahRekeningModal').querySelector('.color-picker');
                    tambahIconGrid.querySelectorAll('.icon-item').forEach(el => el.classList.remove('active'));
                    tambahColorPicker.querySelectorAll('.color-item').forEach(el => el.classList.remove('active'));
                    tambahIconGrid.querySelector('[data-icon="bi-wallet2"]').classList.add('active');
                    tambahColorPicker.querySelector('[data-color="#3b82f6"]').classList.add('active');
                    document.getElementById('selectedIcon').value = 'bi-wallet2';
                    document.getElementById('selectedColor').value = '#3b82f6';
                    loadRekeningFromDatabase();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
        });

        rekeningList.addEventListener('click', function(e) {
            const editButton = e.target.closest('.action-link.edit');
            if (editButton) {
                e.preventDefault();

                const rekeningId = editButton.dataset.id;
                fetch(`/rekening/${rekeningId}`)
                    .then(response => response.json())
                    .then(rekening => {
                        editRekeningId.value = rekening.id;
                        editNamaRekening.value = rekening.nama_rekening;
                        editSaldoAwal.value = rekening.saldo;
                        editMataUang.value = rekening.mata_uang;

                        editIconGrid.querySelectorAll('.icon-item').forEach(item => {
                            item.classList.remove('active');
                            if (item.dataset.icon === rekening.icon) {
                                item.classList.add('active');
                            }
                        });
                        editSelectedIcon.value = rekening.icon;

                        editColorPicker.querySelectorAll('.color-item').forEach(item => {
                            item.classList.remove('active');
                            if (item.dataset.color === rekening.warna) {
                                item.classList.add('active');
                            }
                        });
                        editSelectedColor.value = rekening.warna;

                        editRekeningModal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching rekening for edit:', error);
                        alert('Gagal memuat data rekening untuk diedit.');
                    });
            }
        });

        editRekeningForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const rekeningId = editRekeningId.value;
            const updatedNamaRekening = editNamaRekening.value;
            const updatedSaldo = parseFloat(editSaldoAwal.value) || 0;
            const updatedMataUang = editMataUang.value;
            const updatedIcon = editSelectedIcon.value;
            const updatedWarna = editSelectedColor.value;

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
                    loadRekeningFromDatabase();
                })
                .catch(error => {
                    console.error('Error updating rekening:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
        });
    });
</script>
@endpush