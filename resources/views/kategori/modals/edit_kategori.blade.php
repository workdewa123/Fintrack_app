<div class="modal fade" id="editKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- ID form harus sesuai dengan yang dipanggil di script kategori.blade.php --}}
               <form id="editKategoriForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT"> <input type="hidden" id="edit_id_kategori">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" id="edit_nama_kategori" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tipe Kategori</label>
                        <select class="form-select" name="tipe_kategori" id="edit_tipe_kategori">
                            <option value="pemasukan">Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>