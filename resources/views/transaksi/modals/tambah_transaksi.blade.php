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
                    {{-- Ganti bagian Jenis Transaksi di tambah_transaksi.blade.php --}}
                    <div class="mb-3">
                        <label for="jenisTransaksi" class="form-label small fw-bold text-muted">JENIS TRANSAKSI (OTOMATIS)</label>
                        <select class="form-select bg-light border-0 shadow-none" id="jenisTransaksi" disabled>
                            <option value="">Pilih Kategori Terlebih Dahulu</option>
                            <option value="MASUK">Pemasukan</option>
                            <option value="KELUAR">Pengeluaran</option>
                        </select>
                        {{-- Input hidden ini yang akan dikirim ke Controller --}}
                        <input type="hidden" name="tipe" id="jenisTransaksiHidden">
                    </div>
                    <div class="mb-3">
                        <label for="tanggalWaktu" class="form-label">Tanggal dan Waktu</label>
                        <input type="datetime-local" class="form-control" id="tanggalWaktu" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="id_kategori" required>
                            <option disabled selected>Pilih Kategori</option>
                            {{-- Options akan diisi oleh JavaScript --}}
                        </select>
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