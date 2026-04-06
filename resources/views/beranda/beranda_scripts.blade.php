<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. INISIALISASI CHART GARIS (Arus Kas) ---
        const lineCtx = document.getElementById('lineChart');
        let lineChart;
        if (lineCtx) {
            lineChart = new Chart(lineCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{}
                            label: 'Masuk',
                            borderColor: '#4ade80',
                            backgroundColor: 'rgba(74, 222, 128, 0.1)',
                            fill: true,
                            data: [],
                            tension: 0.4
                        },
                        {
                            label: 'Keluar',
                            borderColor: '#f87171',
                            backgroundColor: 'rgba(248, 113, 113, 0.1)',
                            fill: true,
                            data: [],
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });
        }

        // --- 2. INISIALISASI CHART DONUT (Ringkasan Dana) ---
        const pieCtx = document.getElementById('pieChart');
        let pieChart;
        if (pieCtx) {
            pieChart = new Chart(pieCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Masuk', 'Keluar'],
                    datasets: [{
                        data: [0, 0],
                        backgroundColor: ['#4ade80', '#f87171']
                    }]
                },
                options: {
                    cutout: '80%',
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // --- 3. FUNGSI AMBIL DATA DASHBOARD (Saldo & Chart) ---
        window.fetchDashboardData = function() {
            fetch('/api/beranda-data')
                .then(res => res.json())
                .then(data => {
                    // Update Angka di Card
                    if (document.getElementById('totalSaldoBeranda'))
                        document.getElementById('totalSaldoBeranda').innerText = formatRupiah(data.totalSaldo);
                    if (document.getElementById('totalPemasukanBeranda'))
                        document.getElementById('totalPemasukanBeranda').innerText = formatRupiah(data.totalPemasukan);
                    if (document.getElementById('totalPengeluaranBeranda'))
                        document.getElementById('totalPengeluaranBeranda').innerText = formatRupiah(data.totalPengeluaran);

                    // Update Persentase & Label sesuai UI Mockup
                    let total = data.totalPemasukan + data.totalPengeluaran;
                    let inPct = total > 0 ? Math.round((data.totalPemasukan / total) * 100) : 0;
                    let outPct = total > 0 ? 100 - inPct : 0;

                    // ID ini harus ada di beranda.blade.php kamu
                    if (document.getElementById('pemasukanPercentage'))
                        document.getElementById('pemasukanPercentage').innerText = inPct + '%';

                    // ID untuk label di bawah chart (Masuk/Keluar)
                    if (document.getElementById('labelPemasukan'))
                        document.getElementById('labelPemasukan').innerText = inPct + '%';
                    if (document.getElementById('labelPengeluaran'))
                        document.getElementById('labelPengeluaran').innerText = outPct + '%';

                    // Update Visual Chart
                    if (pieChart) {
                        pieChart.data.datasets[0].data = [data.totalPemasukan, data.totalPengeluaran];
                        pieChart.update();
                    }
                    if (lineChart && data.chartLine) {
                        lineChart.data.datasets[0].data = data.chartLine.masuk;
                        lineChart.data.datasets[1].data = data.chartLine.keluar;
                        lineChart.update();
                    }
                });
        };

        // --- 4. LOAD REKENING KE DROPDOWN MODAL (Sesuai UI Tambah Transaksi) ---
        function loadRekeningToModal() {
            fetch('/rekening-data')
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('rekening');
                    if (select) {
                        select.innerHTML = '<option disabled selected>Pilih Rekening</option>';
                        data.forEach(r => {
                            select.innerHTML += `<option value="${r.id}">${r.nama_rekening} (Rp. ${r.saldo.toLocaleString()})</option>`;
                        });
                    }
                });
        }

        // Jalankan fungsi saat startup
        fetchDashboardData();
        loadRekeningToModal();
    });
</script>