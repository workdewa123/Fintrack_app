@extends('layout.app')

@section('content')
<style>
    /* Styling for the summary cards to match the design */
    .total-rekening {
        background-color: #3b82f6;
        color: white;
    }

    .total-pemasukan {
        background-color: #e8f9ef;
        /* Warna hijau muda */
        color: #333;
    }

    .total-pengeluaran {
        background-color: #fdeaea;
        /* Warna merah muda */
        color: #333;
    }

    .card {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1rem;
    }

    .card-text {
        font-size: 2.5rem;
        font-weight: 700;
        margin-top: 1rem !important;
    }

    /* Additional styling for the new modal */
    .modal-content {
        border-radius: 1rem;
    }

    /* Styles to match the new form layout */
    .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-row>.col {
        flex: 1;
    }

    .form-group-full {
        margin-bottom: 1rem;
    }

    /* Chart specific styling */
    .chart-container {
        height: 300px;
    }
</style>

<div class="beranda-container">
    <div class="mb-4">
        <h3 class="fw-bold">Beranda</h3>
        <p class="text-muted">Pantau semua saldo, pemasukan, pengeluaran, dan kategori keuangan secara ringkas</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card total-rekening h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold">Total Saldo Seluruh Rekening</h6>
                    <h4 class="card-text mt-3 fw-bold" id="totalSaldoBeranda">Rp. 0</h4>
                    <a href="{{ url('/rekening') }}" class="btn btn-light btn-sm mt-auto">Lihat Rekening</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card total-pemasukan h-100">
                <div class="card-body">
                    <h6 class="card-title fw-bold">Total Pemasukan Bulan Ini</h6>
                    <h4 class="card-text mt-3 fw-bold" id="totalPemasukanBeranda" style="color: #4ade80;">Rp. 0</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card total-pengeluaran h-100">
                <div class="card-body">
                    <h6 class="card-title fw-bold">Total Pengeluaran Bulan Ini</h6>
                    <h4 class="card-text mt-3 fw-bold" id="totalPengeluaranBeranda" style="color: #f87171;">Rp. 0</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0">Grafik Perjalanan Keuangan</h6>
                        <select class="form-select form-select-sm w-auto">
                            <option selected>2025</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0">Statistik Kategori Transaksi</h6>
                        <select class="form-select form-select-sm w-auto">
                            <option selected>Bulan Ini</option>
                        </select>
                    </div>
                    <div class="d-flex align-items-center justify-content-center flex-grow-1">
                        <div style="width: 160px; height: 160px;">
                            <canvas id="pieChart"></canvas>
                        </div>
                        <div class="ms-4">
                            <div class="d-flex align-items-center mb-2">
                                <span style="background-color: #4ade80; width: 12px; height: 12px; border-radius: 4px; display: inline-block; margin-right: 8px;"></span>
                                Masuk <span class="fw-bold ms-2" id="pemasukanPercentage">0%</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span style="background-color: #f87171; width: 12px; height: 12px; border-radius: 4px; display: inline-block; margin-right: 8px;"></span>
                                Keluar <span class="fw-bold ms-2" id="pengeluaranPercentage">0%</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 mt-3 py-2" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Transaksi Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Memanggil modal dari file terpisah --}}
@include('beranda.tambah_transaksi')
@endsection

@section('scripts')
{{-- Memanggil skrip JavaScript dari file terpisah --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('beranda.beranda_scripts')
@endsection