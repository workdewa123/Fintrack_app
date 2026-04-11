<div class="modal fade" id="notifikasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold mb-0">Tagihan Mendatang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="list-group list-group-flush">
                    @if(isset($tagihanGlobal) && $tagihanGlobal->count() > 0)
                        @foreach($tagihanGlobal as $tagihan)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $tagihan->nama_pembayaran }}</h6>
                                <small class="text-muted">Jatuh tempo: {{ \Carbon\Carbon::parse($tagihan->tanggal_mulai)->format('d M') }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-danger mb-1" style="font-size: 0.9rem;">
                                    Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}
                                </div>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button onclick="showDetailModal({{ $tagihan->id_pengingat }})" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        Detail
                                    </button>
                                    
                                    <button onclick="bayarTagihanGlobal({{ $tagihan->id_pengingat }})" class="btn btn-primary btn-sm rounded-pill px-3">
                                        Bayar
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <iconify-icon icon="line-md:calendar-check" style="font-size: 3rem;" class="text-muted mb-2"></iconify-icon>
                            <p class="text-muted mb-0">Tidak ada tagihan dalam waktu dekat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>