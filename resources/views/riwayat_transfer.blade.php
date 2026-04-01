@extends('layout.app')

@section('content')
<style>
    .transfer-history-container {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .time-filter-btn {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        background: white;
        color: #4b5563;
        margin-right: 0.5rem;
    }

    .time-filter-btn.active {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-success {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-failed {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .transfer-history-item {
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 0;
    }

    .transfer-history-item:last-child {
        border-bottom: none;
    }

    .back-button {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div class="transfer-history-container">
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold">Riwayat Transfer</h3>
                <p class="text-muted">Riwayat transfer untuk rekening: <strong>{{ $rekening->nama_rekening ?? 'Semua Rekening' }}</strong></p>
            </div>
            <a href="{{ route('rekening.index') }}" class="btn btn-outline-secondary back-button">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-light-success border-0">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Total Masuk</h6>
                    <h4 class="fw-bold text-success">Rp. {{ number_format($totalMasuk, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light-danger border-0">
                <div class="card-body text-center">
                    <h6 class="card-title text-muted">Total Keluar</h6>
                    <h4 class="fw-bold text-danger">Rp. {{ number_format($totalKeluar, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4 rounded-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="btn-group" role="group">
                    <button type="button" class="time-filter-btn active" data-filter="today">Hari Ini</button>
                    <button type="button" class="time-filter-btn" data-filter="week">Minggu Ini</button>
                    <button type="button" class="time-filter-btn" data-filter="month">Bulan Ini</button>
                </div>
            </div>

            <div class="transfer-history-list">
                @if(count($transfers) > 0)
                @foreach($transfers as $transfer)
                <div class="transfer-history-item">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <small class="text-muted">{{ \Carbon\Carbon::parse($transfer->tanggal_transfer)->format('d M Y, H:i') }}</small>
                        </div>
                        <div class="col-md-3">
                            <div class="fw-medium">
                                {{ $transfer->rekeningAsal->nama_rekening }} → {{ $transfer->rekeningTujuan->nama_rekening }}
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="fw-bold">Rp. {{ number_format($transfer->jumlah, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="status-badge {{ $transfer->status == 'success' ? 'status-success' : 'status-failed' }}">
                                {{ $transfer->status == 'success' ? 'Berhasil' : 'Gagal' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox display-4"></i>
                    <p class="mt-2">Tidak ada riwayat transfer</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter waktu
        document.querySelectorAll('.time-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.time-filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                // Implementasi filter di sini (AJAX call atau reload page dengan parameter)
                window.location.href = '{{ request()->url() }}?filter=' + filter + '&rekening_id={{ request()->rekening_id }}';
            });
        });
    });
</script>
@endpush