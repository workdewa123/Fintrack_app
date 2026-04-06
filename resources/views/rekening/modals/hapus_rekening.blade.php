{{-- hapus_rekening.blade.php --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Montserrat:wght@700&display=swap" rel="stylesheet">

<div class="modal fade" id="hapusRekeningModal" tabindex="-1" aria-labelledby="hapusRekeningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow" style="background: transparent;">
            <div class="modal-body p-0">
                <div class="p-4 rounded-4" style="background: #eaf6fb; border-radius: 18px;">

                    {{-- Judul --}}
                    <div class="text-center mb-3">
                        <h2 class="mb-0" style="font-family: 'Montserrat', sans-serif; font-weight:700; font-size:28px; color:#0b2b3a;">
                            Hapus Rekening
                        </h2>
                    </div>

                    {{-- Box konfirmasi --}}
                    <div class="d-flex justify-content-center mb-4">
                        <div style="max-width:680px; width:100%;">
                            <div style="border:1px solid #111827; border-radius:12px; padding:14px 18px; background:#fff; font-family: 'Inter', sans-serif; font-size:16px; color:#0b2b3a; text-align:left;">
                                Apakah anda yakin akan menghapus rekening
                                <span style="color:#e11d48; font-weight:600;">“<span id="hapusRekeningNama">REKENING</span>”</span>?
                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-light rounded-pill px-4 me-3" data-bs-dismiss="modal" style="font-family:'Inter',sans-serif;">
                            Batal
                        </button>

                        {{-- Ganti bagian form di hapus_rekening.blade.php dengan ini --}}
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-light rounded-pill px-4 me-3" data-bs-dismiss="modal">
                                Batal
                            </button>

                            {{-- Gunakan ID deleteRekeningId agar cocok dengan window.executeDelete --}}
                            <input type="hidden" id="deleteRekeningId"> 
                            
                            <button type="button" class="btn btn-danger rounded-pill px-4" onclick="executeDelete()">
                                Hapus Sekarang
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var hapusModalEl = document.getElementById('hapusRekeningModal');
        if (!hapusModalEl) return;

        // Saat modal dibuka
        hapusModalEl.addEventListener('show.bs.modal', function(event) {
            var trigger = event.relatedTarget;
            if (!trigger) return;

            var rekeningId = trigger.getAttribute('data-id') || '';
            var rekeningName = trigger.getAttribute('data-name') || 'Rekening';

            // isi form
            document.getElementById('hapusRekeningIdInput').value = rekeningId;
            document.getElementById('hapusRekeningNama').textContent = rekeningName;

            // set action form
            document.getElementById('formHapusRekening').action = '/rekening/' + encodeURIComponent(rekeningId);
        });

        // handle submit
        const form = document.getElementById('formHapusRekening');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('btnHapusRekening');
            btn.disabled = true;
            btn.textContent = 'Menghapus...';

            fetch(form.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal menghapus');
                    return res.json();
                })
                .then(data => {
                    // ✅ TUTUP MODAL DENGAN CARA YANG BENAR
                    const modal = bootstrap.Modal.getInstance(hapusModalEl) || new bootstrap.Modal(hapusModalEl);
                    modal.hide();

                    // Hapus baris rekening dari tabel (opsional)
                    if (data.id) {
                        const row = document.getElementById('row-rekening-' + data.id);
                        if (row) row.remove();
                    }

                    alert(data.message || 'Rekening berhasil dihapus.');
                })
                .catch(err => {
                    alert('Terjadi kesalahan saat menghapus rekening: ' + err.message);
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = 'Hapus';
                });
        });
    });
</script>
@endpush