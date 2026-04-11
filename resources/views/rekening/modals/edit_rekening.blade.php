<div class="modal fade" id="editRekeningModal" tabindex="-1" aria-labelledby="editRekeningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow border-0" style="background-color: #f8fafc;">
            <style>
                #editRekeningModal .modal-header {
                    border-bottom: none;
                    padding-top: 1.5rem;
                    padding-left: 1.5rem;
                }

                #editRekeningModal .modal-title {
                    color: #1e293b;
                    font-size: 1.25rem;
                }

                #editRekeningModal .form-label {
                    font-weight: 600;
                    color: #475569;
                    font-size: 0.9rem;
                }

                #editRekeningModal .form-control,
                #editRekeningModal .form-select {
                    border-radius: 0.75rem;
                    border: 1px solid #e2e8f0;
                    padding: 0.7rem 1rem;
                    background-color: #f1f5f9;
                    color: #1e293b;
                    transition: all 0.2s;
                }

                #editRekeningModal .form-control:focus,
                #editRekeningModal .form-select:focus {
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
                    background-color: #ffffff;
                }

                #editRekeningModal .icon-item {
                    width: 45px;
                    height: 45px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border-radius: 12px;
                    border: 2px solid #e2e8f0;
                    background-color: #ffffff;
                    color: #94a3b8;
                    font-size: 1.25rem;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                #editRekeningModal .icon-item.active {
                    background-color: #eff6ff;
                    border-color: #3b82f6;
                    color: #3b82f6;
                }

                /* Styling untuk Warna */
                #editRekeningModal .color-item {
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    border: 3px solid #ffffff;
                    box-shadow: 0 0 0 1px #e2e8f0;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                #editRekeningModal .color-item.active {
                    box-shadow: 0 0 0 2px #3b82f6;
                    transform: scale(1.1);
                }

                #editRekeningModal .btn-primary {
                    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
                    border: none;
                    border-radius: 0.75rem;
                    padding: 0.75rem;
                    font-weight: 700;
                }

                #editRekeningModal .btn-light {
                    background-color: #e2e8f0;
                    border: none;
                    border-radius: 0.75rem;
                    padding: 0.75rem;
                    color: #475569;
                    font-weight: 600;
                }
            </style>

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="editRekeningModalLabel">Edit Rekening</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editRekeningForm">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" id="editRekeningId">
                    <input type="hidden" name="icon" id="editSelectedIcon">
                    <input type="hidden" name="warna" id="editSelectedColor">

                    <div class="mb-3">
                        <label for="editNamaRekening" class="form-label">Nama Rekening</label>
                        <input type="text" class="form-control" id="editNamaRekening" name="nama_rekening" required>
                    </div>

                    <div class="mb-3">
                        <label for="editSaldoAwal" class="form-label">Saldo</label>
                        <input type="number" class="form-control" id="editSaldoAwal" name="saldo" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="editMataUang" class="form-label">Mata Uang</label>
                        <select class="form-select" id="editMataUang" name="mata_uang" required>
                            <option value="IDR">IDR - Rupiah</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Ikon</label>
                        <div class="d-flex flex-wrap gap-2 icon-grid">
                            <div class="icon-item" data-icon="bi-wallet2"><i class="bi bi-wallet2"></i></div>
                            <div class="icon-item" data-icon="bi-bank"><i class="bi bi-bank"></i></div>
                            <div class="icon-item" data-icon="bi-cash-coin"><i class="bi bi-cash-coin"></i></div>
                            <div class="icon-item" data-icon="bi-credit-card"><i class="bi bi-credit-card"></i></div>
                            <div class="icon-item" data-icon="bi-piggy-bank"><i class="bi bi-piggy-bank"></i></div>
                            <div class="icon-item" data-icon="bi-currency-exchange"><i class="bi bi-currency-exchange"></i></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Warna</label>
                        <div class="d-flex flex-wrap gap-2 color-picker">
                            <div class="color-item" data-color="#3b82f6" style="background-color: #3b82f6;"></div>
                            <div class="color-item" data-color="#22c55e" style="background-color: #22c55e;"></div>
                            <div class="color-item" data-color="#f59e0b" style="background-color: #f59e0b;"></div>
                            <div class="color-item" data-color="#ef4444" style="background-color: #ef4444;"></div>
                            <div class="color-item" data-color="#6b7280" style="background-color: #6b7280;"></div>
                            <div class="color-item" data-color="#8b5cf6" style="background-color: #8b5cf6;"></div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill">Simpan Perubahan</button>
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>