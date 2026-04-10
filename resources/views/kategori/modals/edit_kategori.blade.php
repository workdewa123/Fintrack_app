<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="
            border-radius: 2rem;
            background: linear-gradient(135deg, #E0EAFC 0%, #CFDEF3 100%);
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        ">
            <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(59, 130, 246, 0.05); border-radius: 50%; z-index: 0;"></div>

            <div class="modal-header border-0 pb-0" style="position: relative; z-index: 1;">
                <div class="d-flex flex-column">
                    <h5 class="modal-title fw-bold" style="font-size: 1.5rem; color: #1e293b;">Edit Kategori</h5>
                    <p class="text-muted small mb-0">Perbarui label keuangan Anda</p>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body py-4" style="position: relative; z-index: 1;">
                <form id="editKategoriForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" id="edit_id_kategori">

                    <div class="mb-4">
                        <label class="form-label ps-1" style="font-weight: 600; color: #475569; font-size: 0.9rem;">Nama Kategori</label>
                        <div class="input-group" style="background: white; border-radius: 1rem; border: 1px solid #e2e8f0; overflow: hidden;">
                            <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                                <iconify-icon icon="ic:round-label" style="font-size: 1.2rem;"></iconify-icon>
                            </span>
                            <input type="text" class="form-control border-0 py-3 shadow-none" name="nama_kategori" id="edit_nama_kategori" required style="font-size: 0.95rem; background: transparent;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label ps-1" style="font-weight: 600; color: #475569; font-size: 0.9rem;">Tipe Transaksi</label>
                        <div class="row g-3">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="tipe_kategori" id="edit_typeMasuk" value="pemasukan">
                                <label class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center gap-2" for="edit_typeMasuk" style="border-radius: 1rem; border-width: 2px; font-weight: 600;">
                                    <iconify-icon icon="ic:round-trending-up" style="font-size: 1.5rem;"></iconify-icon>
                                    <span>Pemasukan</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="tipe_kategori" id="edit_typeKeluar" value="pengeluaran">
                                <label class="btn btn-outline-danger w-100 py-3 d-flex flex-column align-items-center gap-2" for="edit_typeKeluar" style="border-radius: 1rem; border-width: 2px; font-weight: 600;">
                                    <iconify-icon icon="ic:round-trending-down" style="font-size: 1.5rem;"></iconify-icon>
                                    <span>Pengeluaran</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-5">
                        <button type="button" class="btn btn-light py-3" data-bs-dismiss="modal" style="flex: 1; border-radius: 1rem; font-weight: 700; color: #64748b; background-color: #f1f5f9; border: none;">Batal</button>
                        <button type="submit" class="btn btn-primary py-3 shadow-sm" style="flex: 1.5; border-radius: 1rem; font-weight: 700; background-color: #3b82f6; border: none; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>