{{-- Modal Edit Transaksi --}}
<div class="modal fade" id="editTransactionModal" tabindex="-1" aria-labelledby="editTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 1rem;">
            <div class="modal-header">
                <h5 class="modal-title" id="editTransactionModalLabel">Edit Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTransactionForm">
                    <input type="hidden" id="editTransaksiId">
                    
                    <div class="mb-3">
                        <label for="editJenisTransaksi" class="form-label small fw-bold text-muted">JENIS TRANSAKSI (OTOMATIS)</label>
                        <select class="form-select bg-light border-0 shadow-none" id="editJenisTransaksi" disabled>
                            <option value="MASUK">Pemasukan</option>
                            <option value="KELUAR">Pengeluaran</option>
                        </select>
                        <input type="hidden" id="editJenisTransaksiHidden">
                    </div>

                    <div class="mb-3">
                        <label for="editTanggalWaktu" class="form-label">Tanggal dan Waktu</label>
                        <input type="datetime-local" class="form-control" id="editTanggalWaktu" required>
                    </div>

                    <div class="mb-3">
                        <label for="editKategori" class="form-label">Kategori</label>
                        <select class="form-select" id="editKategori" required>
                            {{-- Diisi via JS --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editJumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="editJumlah" required>
                    </div>

                    <div class="mb-3">
                        <label for="editRekening" class="form-label">Rekening</label>
                        <select class="form-select" id="editRekening" required>
                            {{-- Diisi via JS --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editCatatan" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="editCatatan" rows="3"></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>