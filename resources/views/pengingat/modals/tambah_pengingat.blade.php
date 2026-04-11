<div class="modal fade" id="addPengingatModal" tabindex="-1" aria-labelledby="addPengingatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 p-4 pb-0">
                <div class="w-100 text-center">
                    <h4 class="fw-bold mb-0" id="addPengingatModalLabel">Buat Pengingat Baru</h4>
                    <p class="text-muted small mb-0">Atur jadwal transaksi otomatis Anda</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 p-md-5">
                <form id="addPengingatForm">
                    @csrf
                    
                    <input type="hidden" name="tipe" value="Pengeluaran">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Pembayaran</label>
                                <input type="text" name="nama_pembayaran" id="modalNama" class="form-control" placeholder="Mis. Tagihan Listrik" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Frekuensi Pengingat</label>
                                <select name="frekuensi" id="modalFrekuensi" class="form-select">
                                    <option value="HARIAN">Harian</option>
                                    <option value="MINGGUAN">Mingguan</option>
                                    <option value="BULANAN">Bulanan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Jumlah Pembayaran (Rp)</label>
                                <input type="number" name="jumlah" id="modalJumlah" class="form-control" placeholder="0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Rekening</label>
                                <select class="form-select" name="id_rekening" id="modalRekening" required>
                                    <option value="" disabled selected>Pilih Rekening</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Kategori (Khusus Pengeluaran)</label>
                                <select class="form-select" name="id_kategori" id="modalKategori" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                </select>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tgl Mulai</label>
                                        <input type="date" name="tanggal_mulai" id="modalTglMulai" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Tgl Akhir</label>
                                        <input type="date" name="tanggal_akhir" id="modalTglAkhir" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Catatan</label>
                                <textarea name="komentar" id="modalKomentar" class="form-control" rows="3" placeholder="Keterangan..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom px-4">Simpan Pengingat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>