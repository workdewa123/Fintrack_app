@extends('layout.app')

@section('content')
<style>
    /* Styling untuk card ringkasan agar sesuai dengan desain */
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
        font-family: 'Inter', sans-serif;
    }

    .card-text {
        font-size: 2.5rem;
        font-weight: 700;
        margin-top: 1rem !important;
        font-family: 'Inter', sans-serif;
    }

    /* Gaya tambahan untuk modal baru */
    .modal-backdrop.show {
        background-color: rgba(22, 101, 216, 0.5);
        /* Warna biru semi-transparan */
        backdrop-filter: blur(4px);
        /* Efek blur pada latar belakang */
    }

    .modal-content {
        border-radius: 1rem;
        background-color: #EBF8FF;
        /* Latar belakang modal baru */
    }

    .modal-footer .btn-secondary {
        background-color: #d1d5db;
        /* Warna abu-abu yang lebih lembut */
        border-color: #d1d5db;
        color: #6b7280;
    }

    .modal-footer .btn-primary {
        background-color: #3b82f6;
        /* Warna biru yang sama dengan card */
        border-color: #3b82f6;
    }

    /* Gaya untuk mencocokkan tata letak formulir baru */
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

    /* Gaya khusus untuk bagan */
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

<!-- Modal untuk Tambah Transaksi Baru -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransactionModalLabel">Tambah Transaksi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTransactionForm">
                    <div class="form-row">
                        <div class="col">
                            <label for="jenis">Jenis Transaksi</label>
                            <select class="form-select" id="jenis" name="jenis" required>
                                <option value="">Pilih Jenis</option>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="tanggal">Tanggal dan Waktu</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                        </div>
                    </div>
                    <div class="form-group-full">
                        <label for="kategori">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="">Pilih Kategori</option>
                            <!-- Pilihan akan diisi secara dinamis -->
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" placeholder="Rp." required>
                        </div>
                        <div class="col">
                            <label for="rekening">Rekening</label>
                            <select class="form-select" id="rekening" name="rekening" required>
                                <option value="">Pilih Rekening</option>
                                <!-- Pilihan akan diisi secara dinamis -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group-full">
                        <label for="deskripsi">Catatan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Inisialisasi ChartJS
    window.addEventListener('load', function() {
        // Data placeholder untuk grafik
        var lineChartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pemasukan',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], // Data pemasukan kosong
                borderColor: '#4ade80',
                backgroundColor: 'rgba(74, 222, 128, 0.2)',
                tension: 0.3,
                fill: true
            }, {
                label: 'Pengeluaran',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], // Data pengeluaran kosong
                borderColor: '#f87171',
                backgroundColor: 'rgba(248, 113, 113, 0.2)',
                tension: 0.3,
                fill: true
            }]
        };

        var pieChartData = {
            labels: ['Pemasukan', 'Pengeluaran'],
            datasets: [{
                data: [0, 0], // Data kosong untuk pie chart
                backgroundColor: ['#4ade80', '#f87171'],
                hoverOffset: 4
            }]
        };

        // Konfigurasi Chart Garis
        var lineChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp. ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        };

        // Konfigurasi Chart Donat (Pie)
        var pieChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            cutout: '80%',
        };

        // Gambar chart menggunakan canvas element
        var lineCtx = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(lineCtx, {
            type: 'line',
            data: lineChartData,
            options: lineChartOptions
        });

        var pieCtx = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: pieChartData,
            options: pieChartOptions
        });
    });
</script>
@endsection