<div class="modal fade" id="detailPengingatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold mb-0">Detail Pengingat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="badge-custom badge-expense d-inline-block mb-2">Pengeluaran</div>
                    <h3 class="fw-bold text-dark mb-1" id="detNama">Nama Pembayaran</h3>
                    <h2 class="fw-bold text-danger" id="detJumlah">Rp 0</h2>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <label class="small text-muted d-block">Frekuensi</label>
                        <span class="fw-bold" id="detFrekuensi">-</span>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted d-block">Kategori</label>
                        <span class="fw-bold" id="detKategori">-</span>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted d-block">Rekening</label>
                        <span class="fw-bold" id="detRekening">-</span>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted d-block">Status</label>
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">Aktif</span>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted d-block">Tanggal Mulai</label>
                        <span class="fw-bold" id="detTglMulai">-</span>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted d-block">Tanggal Akhir</label>
                        <span class="fw-bold" id="detTglAkhir">-</span>
                    </div>
                    <div class="col-12">
                        <label class="small text-muted d-block">Catatan</label>
                        <p class="mb-0 italic text-secondary" id="detKomentar">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" 
                        class="btn btn-light rounded-pill w-100 fw-bold" 
                        data-bs-dismiss="modal"
                        data-bs-toggle="modal" 
                        data-bs-target="#notifikasiModal">
                    Kembali ke Notifikasi
                </button>
                <button type="button" class="btn btn-light rounded-pill w-100 fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
            
        </div>
    </div>
</div>