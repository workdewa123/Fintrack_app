{{-- Modal Tambah Transaksi Baru --}}
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 1rem;">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransactionModalLabel">Tambah Transaksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTransactionForm">
                    <div class="mb-3">
                        <label for="jenisTransaksi" class="form-label">Jenis Transaksi</label>
                        <select class="form-select" id="jenisTransaksi" required>
                            <option value="pengeluaran">Pengeluaran</option>
                            <option value="pemasukan">Pemasukan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggalWaktu" class="form-label">Tanggal dan Waktu</label>
                        <input type="datetime-local" class="form-control" id="tanggalWaktu" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" placeholder="Pilih Kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" placeholder="Masukkan jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="rekening" class="form-label">Rekening</label>
                        <select class="form-select" id="rekening" required>
                            <option disabled selected>Pilih Rekening</option>
                            {{-- Options will be populated by JS --}}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" id="catatan" rows="3" placeholder="Catatan (opsional)"></textarea>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary rounded-pill">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>