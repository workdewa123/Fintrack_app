{{-- hapus_rekening.blade.php --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Custom CSS khusus untuk Modal Hapus */
    #hapusRekeningModal .modal-content {
        border-radius: 1.25rem;
        overflow: hidden;
        border: none;
    }

    #hapusRekeningModal .modal-body {
        padding: 2.5rem 2rem;
        background-color: #ffffff;
    }

    /* Icon Peringatan */
    .delete-icon-wrapper {
        width: 64px;
        height: 64px;
        background-color: #fef2f2;
        color: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto 1.5rem;
    }

    #hapusRekeningModal .modal-title-custom {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.25rem;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    #hapusRekeningModal .modal-desc {
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        color: #6b7280;
        line-height: 1.5;
    }

    /* Highlight Nama Rekening */
    #hapusRekeningNama {
        color: #111827;
        font-weight: 600;
        background-color: #f3f4f6;
        padding: 0.1rem 0.4rem;
        border-radius: 4px;
    }

    /* Button Styling */
    .btn-delete-confirm {
        background-color: #ef4444;
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.2s;
    }

    .btn-delete-confirm:hover {
        background-color: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }

    .btn-cancel-custom {
        background-color: #f3f4f6;
        border: none;
        color: #4b5563;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.2s;
    }

    .btn-cancel-custom:hover {
        background-color: #e5e7eb;
        color: #1f2937;
    }
</style>

<div class="modal fade" id="hapusRekeningModal" tabindex="-1" aria-labelledby="hapusRekeningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
        <div class="modal-content shadow-lg">
            <div class="modal-body text-center">

                {{-- Visual Icon Peringatan --}}
                <div class="delete-icon-wrapper">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                {{-- Judul & Deskripsi --}}
                <h2 class="modal-title-custom">Hapus Rekening?</h2>
                <p class="modal-desc">
                    Apakah Anda yakin ingin menghapus rekening <span id="hapusRekeningNama">REKENING</span>? Tindakan ini tidak dapat dibatalkan.
                </p>

                {{-- Group Tombol --}}
                <div class="d-grid gap-2 mt-4">
                    <input type="hidden" id="deleteRekeningId">
                    <button type="button" class="btn btn-delete-confirm" onclick="executeDelete()">
                        Ya, Hapus Sekarang
                    </button>
                    <button type="button" class="btn btn-cancel-custom" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>