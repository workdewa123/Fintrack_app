<div class="modal fade" id="editKategoriModal{{ $category->id_kategori }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- PERBAIKAN UTAMA: Gunakan id_kategori dan tambahkan @method('PUT') --}}
                <form action="{{ route('kategori.update', $category->id_kategori) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" name="nama_kategori" 
                            value="{{ $category->nama_kategori }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tipe Kategori</label>
                        <select class="select" name="tipe_kategori">
                            <option value="pemasukan" {{ $category->tipe == 'MASUK' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ $category->tipe == 'KELUAR' ? 'selected' : '' }}>Pengeluaran</option>
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