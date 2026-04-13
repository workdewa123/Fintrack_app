<div class="modal fade" id="modalEditProfil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="fw-bold">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profil') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    {{-- Preview & Upload Foto --}}
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ Auth::user()->foto_profil ? asset('images/profil/'.Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama) }}"
                                 id="previewFoto"
                                 class="rounded-circle border border-3 border-primary"
                                 style="width: 100px; height: 100px; object-fit: cover;">
                            <label for="inputFoto" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; cursor: pointer; border: 3px solid #fff;">
                                <iconify-icon icon="solar:camera-add-bold"></iconify-icon>
                            </label>
                            <input type="file" name="foto_profil" id="inputFoto" class="d-none" accept="image/*">
                        </div>
                        <p class="text-muted small mt-2">Klik ikon kamera untuk ganti foto</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control form-control-lg" style="font-size: 0.9rem;" value="{{ Auth::user()->nama }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Username</label>
                        <input type="text" name="username" class="form-control form-control-lg" style="font-size: 0.9rem;" value="{{ Auth::user()->username }}" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="background: #3b82f6;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Logika preview foto saat file dipilih
    document.getElementById('inputFoto').onchange = function(evt) {
        const [file] = this.files;
        if (file) {
            document.getElementById('previewFoto').src = URL.createObjectURL(file);
        }
    };
</script>
