@extends('layout.app')

@section('content')
<style>
    .total-rekening {
        background-color: #4a90e2;
        color: white;
        border-radius: 1.5rem;
    }

    .total-pemasukan {
        background-color: #e8f9ef;
        color: #333;
        border-radius: 1.5rem;
    }

    .total-pengeluaran {
        background-color: #fdeaea;
        color: #333;
        border-radius: 1.5rem;
    }

    .card {
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .card-text {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .btn-light-custom {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        backdrop-filter: blur(5px);
    }

    .btn-light-custom:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    /* Tambahkan ini di dalam <style> beranda.blade.php */
    .card-text {
        font-size: clamp(1.2rem, 3vw, 2rem) !important; /* Font akan mengecil otomatis jika ruang sempit */
        word-wrap: break-word;
        line-height: 1.2;
    }
</style>

<div class="container-fluid py-4">
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card total-rekening p-4 h-100">
                {{-- GANTI BAGIAN DI DALAM <div class="card-body p-0"> PADA TOTAL SALDO --}}
<h6 class="text-white-50">Total Saldo Seluruh Rekening</h6>
<h2 class="card-text" id="totalSaldoBeranda">Rp. {{ number_format($totalSaldo, 0, ',', '.') }}</h2>

<div class="d-flex flex-wrap gap-2 mt-4">
    <a href="{{ route('rekening.index') }}" class="btn btn-sm btn-light-custom rounded-pill px-3">
        Lihat Rekening
    </a>
    <a href="{{ route('laporan.pdf') }}" class="btn btn-sm btn-light-custom rounded-pill px-3">
        <i class="bi bi-file-pdf"></i> Cetak PDF
    </a>
</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card total-pemasukan p-4 h-100">
                <div class="card-body p-0">
                    <h6 class="text-muted">Total Pemasukan Bulan Ini</h6>
                    <h2 class="card-text text-success" id="totalPemasukanBeranda">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card total-pengeluaran p-4 h-100">
                <div class="card-body p-0">
                    <h6 class="text-muted">Total Pengeluaran Bulan Ini</h6>
                    <h2 class="card-text text-danger" id="totalPengeluaranBeranda">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 h-100 rounded-4">
                <h5 class="fw-bold mb-4">Arus Kas Bulanan</h5>
                <div class="chart-container">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 h-100 rounded-4">
                <h5 class="fw-bold mb-4">Ringkasan Dana Bulan Ini</h5>
                <div class="chart-container d-flex align-items-center justify-content-center">
                    <canvas id="pieChart"></canvas>
                    <div class="position-absolute text-center">
                        <small class="text-muted d-block">Pemasukan</small>
                        <strong id="pemasukanPercentage" class="fs-4">0%</strong>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-circle-fill text-success me-2"></i>Masuk</span>
                        <span id="labelPemasukan">0%</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="bi bi-circle-fill text-danger me-2"></i>Keluar</span>
                        <span id="labelPengeluaran">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@push('scripts')
@include('beranda.beranda_scripts')
@endpush
