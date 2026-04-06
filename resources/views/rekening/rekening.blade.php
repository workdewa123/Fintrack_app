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
                        <th>Mata Uang</th>
                        <th>Saldo</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody id="rekeningTableBody">
                    </tbody>
            </table>
            <div id="emptyState" class="text-center py-5" style="display: none;">
                <p class="text-muted mb-0">Belum ada rekening yang ditemukan.</p>
            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const rekeningTableBody = document.getElementById('rekeningTableBody');
        const emptyState = document.getElementById('emptyState');
        const totalSaldoEl = document.getElementById('totalSaldo');
        const searchInput = document.getElementById('searchInput');

        // Modal Instances
        const tambahRekeningModal = new bootstrap.Modal(document.getElementById('tambahRekeningModal'));
        const editRekeningModal = new bootstrap.Modal(document.getElementById('editRekeningModal'));
        const hapusRekeningModal = new bootstrap.Modal(document.getElementById('hapusRekeningModal'));

        let allRekeningData = [];
        let currentRekeningId = null;

        function loadRekeningFromDatabase() {
            fetch('/rekening-data')
                .then(response => response.json())
                .then(data => {
                    allRekeningData = data;
                    renderRekeningTable(data);
                })
                .catch(err => console.error('Error:', err));
        }

        function renderRekeningTable(data) {
            rekeningTableBody.innerHTML = '';
            let totalSaldo = 0;

            if (data.length === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
                data.forEach(rekening => {
                    const row = document.createElement('tr');
                    row.id = `rekening-${rekening.id_rekening}`;
                    
                    const formattedSaldo = new Intl.NumberFormat('id-ID').format(rekening.saldo);
                    
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="account-icon me-3" style="background-color: ${rekening.warna};">
                                    <i class="bi ${rekening.icon}"></i>
                                </div>
                                <span class="fw-bold text-dark">${rekening.nama_rekening}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">${rekening.mata_uang}</span></td>
                        <td><span class="fw-bold">${rekening.mata_uang === 'IDR' ? 'Rp.' : rekening.mata_uang} ${formattedSaldo}</span></td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="#" class="action-link edit text-success" 
                                   data-id="${rekening.id_rekening}"
                                   data-nama="${rekening.nama_rekening}"
                                   data-saldo="${rekening.saldo}"
                                   data-matauang="${rekening.mata_uang}"
                                   data-icon="${rekening.icon}"
                                   data-warna="${rekening.warna}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="#" class="action-link delete text-danger" 
                                   data-id="${rekening.id_rekening}" 
                                   data-name="${rekening.nama_rekening}"
                                   data-bs-toggle="modal" 
                                   data-bs-target="#hapusRekeningModal">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                                <a href="{{ route('riwayat.transfer') }}?rekening_id=${rekening.id_rekening}" class="action-link history text-primary">
                                    <i class="bi bi-clock-history"></i> Riwayat
                                </a>
                            </div>
                        </td>
                    `;
                    rekeningTableBody.appendChild(row);
                    if (rekening.mata_uang === 'IDR') totalSaldo += parseFloat(rekening.saldo);
                });
            }
            totalSaldoEl.textContent = `Rp. ${new Intl.NumberFormat('id-ID').format(totalSaldo)}`;
        }

        loadRekeningFromDatabase();

        // Search Logic
        searchInput.addEventListener('input', function() {
            const filtered = allRekeningData.filter(r => 
                r.nama_rekening.toLowerCase().includes(this.value.toLowerCase())
            );
            renderRekeningTable(filtered);
        });

        // Setup Icon & Color Pickers
        function setupModalPickers(modalId, iconInputId, colorInputId) {
            const modal = document.getElementById(modalId);
            const iconItems = modal.querySelectorAll('.icon-item');
            const colorItems = modal.querySelectorAll('.color-item');

            iconItems.forEach(item => {
                item.addEventListener('click', function() {
                    iconItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(iconInputId).value = this.dataset.icon;
                });
            });

            colorItems.forEach(item => {
                item.addEventListener('click', function() {
                    colorItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(colorInputId).value = this.dataset.color;
                });
            });
        }

        setupModalPickers('tambahRekeningModal', 'selectedIcon', 'selectedColor');
        setupModalPickers('editRekeningModal', 'editSelectedIcon', 'editSelectedColor');

        // Edit Button Click Logic
        rekeningTableBody.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.action-link.edit');
            if (editBtn) {
                e.preventDefault();
                currentRekeningId = editBtn.dataset.id;
                
                document.getElementById('editNamaRekening').value = editBtn.dataset.nama;
                document.getElementById('editSaldoAwal').value = editBtn.dataset.saldo;
                document.getElementById('editMataUang').value = editBtn.dataset.matauang;
                document.getElementById('editSelectedIcon').value = editBtn.dataset.icon;
                document.getElementById('editSelectedColor').value = editBtn.dataset.warna;

                // Sync Active Classes
                const modal = document.getElementById('editRekeningModal');
                modal.querySelectorAll('.icon-item').forEach(i => {
                    i.classList.toggle('active', i.dataset.icon === editBtn.dataset.icon);
                });
                modal.querySelectorAll('.color-item').forEach(c => {
                    c.classList.toggle('active', c.dataset.color === editBtn.dataset.warna);
                });

                editRekeningModal.show();
            }
        });

        // Submit Add
        document.getElementById('tambahRekeningForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = {
                nama_rekening: document.getElementById('namaRekening').value,
                saldo: document.getElementById('saldoAwal').value,
                mata_uang: document.getElementById('mataUang').value,
                icon: document.getElementById('selectedIcon').value,
                warna: document.getElementById('selectedColor').value,
                _token: '{{ csrf_token() }}'
            };

            fetch('/rekening', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(formData)
            }).then(() => {
                tambahRekeningModal.hide();
                loadRekeningFromDatabase();
                this.reset();
            });
        });

        // Submit Edit
        document.getElementById('editRekeningForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = {
                nama_rekening: document.getElementById('editNamaRekening').value,
                saldo: document.getElementById('editSaldoAwal').value,
                mata_uang: document.getElementById('editMataUang').value,
                icon: document.getElementById('editSelectedIcon').value,
                warna: document.getElementById('editSelectedColor').value,
                _token: '{{ csrf_token() }}'
            };

            fetch(`/rekening/${currentRekeningId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(formData)
            }).then(() => {
                editRekeningModal.hide();
                loadRekeningFromDatabase();
            });
        });

        // Delete Logic
        document.getElementById('formHapusRekening').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('hapusRekeningIdInput').value;
            
            fetch(`/rekening/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => {
                hapusRekeningModal.hide();
                loadRekeningFromDatabase();
            });
        });

        // Modal Delete Trigger
        const modalHapus = document.getElementById('hapusRekeningModal');
        modalHapus.addEventListener('show.bs.modal', function(e) {
            const btn = e.relatedTarget;
            document.getElementById('hapusRekeningIdInput').value = btn.dataset.id;
            this.querySelector('.modal-body span').textContent = btn.dataset.name;
        });
    });
</script>
@endpush