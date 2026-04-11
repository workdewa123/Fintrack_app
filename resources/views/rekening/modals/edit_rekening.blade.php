<div class="modal fade" id="editRekeningModal" tabindex="-1" aria-labelledby="editRekeningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
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