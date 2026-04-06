<div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-labelledby="tambahKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="
            border-radius: 1.5rem;
            background: linear-gradient(180deg, #F0F4FF 0%, #E6F0FF 100%);
            padding: 2.5rem 2rem;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        ">
            <div class="modal-header" style="
                border-bottom: none;
                padding: 0;
                margin-bottom: 2rem;
                position: relative;
            ">
                <h5 class="modal-title" id="tambahKategoriModalLabel" style="font-size: 1.75rem; font-weight: 700; color: #1F2937;">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="
                    position: absolute;
                    right: 0;
                    top: 0;
                    color: #6B7280;
                    font-size: 1.25rem;
                "></button>
            </div>

            <div class="modal-body" style="padding: 0;">
                {{-- PERBAIKAN: Tambahkan Action dan Method --}}
                <form id="tambahKategoriForm" action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="namaKategori" class="form-label" style="font-weight: 600; color: #4A5568; margin-bottom: 0.5rem;">Nama Kategori</label>
                        <input type="text" class="form-control" id="namaKategori" name="nama_kategori" placeholder="Masukkan nama kategori" required style="
                            border-radius: 0.75rem;
                            border: 1px solid #D1D5DB;
                            padding: 0.75rem 1rem;
                            box-shadow: none;
                            background-color: #FFFFFF;
                            color: #1F2937;
                        ">
                    </div>

                    <div class="mb-4">
                        <label for="tipeKategori" class="form-label" style="font-weight: 600; color: #4A5568; margin-bottom: 0.5rem;">Tipe Kategori</label>
                        <select class="form-select" id="tipeKategori" name="tipe_kategori" required style="
                            border-radius: 0.75rem;
                            border: 1px solid #D1D5DB;
                            padding: 0.75rem 1rem;
                            box-shadow: none;
                            background-color: #FFFFFF;
                            color: #1F2937;
                        ">
                            <option value="pemasukan" selected>Pemasukan</option>
                            <option value="pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    {{-- PERBAIKAN: Pindahkan tombol ke dalam tag form untuk kompatibilitas browser yang lebih baik --}}
                    <div class="modal-footer" style="border-top: none; padding: 0; margin-top: 2rem; display: flex; justify-content: center; gap: 1rem;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="
                            background-color: #E2E8F0;
                            border: none;
                            color: #4A5568;
                            border-radius: 0.75rem;
                            padding: 0.75rem 1.5rem;
                            font-weight: 600;
                            flex: 1;
                        ">Batal</button>
                        <button type="submit" class="btn btn-primary" style="
                            background-color: #3B82F6;
                            border: none;
                            color: #FFFFFF;
                            border-radius: 0.75rem;
                            padding: 0.75rem 1.5rem;
                            font-weight: 600;
                            flex: 1;
                        ">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>