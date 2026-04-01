<div class="modal fade" id="tambahRekeningModal" tabindex="-1" aria-labelledby="tambahRekeningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="tambahRekeningModalLabel">Tambah Rekening</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="tambahRekeningForm">
                    <input type="hidden" name="icon" id="selectedIcon" value="bi-wallet2">
                    <input type="hidden" name="warna" id="selectedColor" value="#3b82f6">

                    <div class="mb-3">
                        <label for="namaRekening" class="form-label">Nama Rekening</label>
                        <input type="text" class="form-control" id="namaRekening" name="nama_rekening" placeholder="Nama rekening atau dompet" required>
                    </div>

                    <div class="mb-3">
                        <label for="saldoAwal" class="form-label">Saldo Awal</label>
                        <input type="number" class="form-control" id="saldoAwal" name="saldo" value="0" min="0" required>
                    </div>

                    {{-- PERBAIKAN: MENAMBAHKAN FIELD MATA UANG --}}
                    <div class="mb-3">
                        <label for="mataUang" class="form-label">Mata Uang</label>
                        <select class="form-select" id="mataUang" name="mata_uang" required>
                            <option value="IDR" selected>IDR - Rupiah Indonesia</option>
                            <option value="USD">USD - United States Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Ikon</label>
                        <div class="d-flex flex-wrap gap-2 icon-grid">
                            <div class="icon-item active" data-icon="bi-wallet2"><i class="bi bi-wallet2"></i></div>
                            <div class="icon-item" data-icon="bi-cash-coin"><i class="bi bi-cash-coin"></i></div>
                            <div class="icon-item" data-icon="bi-bank"><i class="bi bi-bank"></i></div>
                            <div class="icon-item" data-icon="bi-credit-card"><i class="bi bi-credit-card"></i></div>
                            <div class="icon-item" data-icon="bi-piggy-bank"><i class="bi bi-piggy-bank"></i></div>
                            <div class="icon-item" data-icon="bi-paypal"><i class="bi bi-paypal"></i></div>
                            <div class="icon-item" data-icon="bi-currency-exchange"><i class="bi bi-currency-exchange"></i></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Warna</label>
                        <div class="d-flex flex-wrap gap-2 color-picker">
                            <div class="color-item active" data-color="#3b82f6" style="background-color: #3b82f6;"></div>
                            <div class="color-item" data-color="#22c55e" style="background-color: #22c55e;"></div>
                            <div class="color-item" data-color="#f59e0b" style="background-color: #f59e0b;"></div>
                            <div class="color-item" data-color="#ef4444" style="background-color: #ef4444;"></div>
                            <div class="color-item" data-color="#6b7280" style="background-color: #6b7280;"></div>
                            <div class="color-item" data-color="#8b5cf6" style="background-color: #8b5cf6;"></div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill">Tambah Rekening</button>
                        <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>