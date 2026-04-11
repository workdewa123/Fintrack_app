<div class="modal fade" id="editPengingatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 p-4 pb-0">
                <div class="w-100 text-center">
                    <h4 class="fw-bold mb-0">Edit Pengingat</h4>
                    <p class="text-muted small mb-0">Perbarui jadwal transaksi rutin Anda</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 p-md-5">
                <form id="editPengingatForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_pengingat" id="editId">
                    <input type="hidden" name="tipe" value="Pengeluaran">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Pembayaran</label>
                                <input type="text" name="nama_pembayaran" id="editNama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Frekuensi Pengingat</label>
                                <select name="frekuensi" id="editFrekuensi" class="form-select" onchange="toggleDetailJadwal(this.value, 'containerDetailEdit')">
                                    <option value="HARIAN">Harian</option>
                                    <option value="MINGGUAN">Mingguan</option>
                                    <option value="BULANAN">Bulanan</option>
                                </select>
                            </div>
                            <div id="containerDetailEdit" class="mt-3"></div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Jumlah Pembayaran (Rp)</label>
                                <input type="number" name="jumlah" id="editJumlah" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Rekening</label>
                                <select class="form-select" name="id_rekening" id="editRekening" required></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Kategori (Khusus Pengeluaran)</label>
                                <select class="form-select" name="id_kategori" id="editKategori" required></select>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tgl Mulai</label>
                                        <input type="date" name="tanggal_mulai" id="editTglMulai" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tgl Akhir</label>
                                        <input type="date" name="tanggal_akhir" id="editTglAkhir" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Catatan</label>
                                <textarea name="komentar" id="editKomentar" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>