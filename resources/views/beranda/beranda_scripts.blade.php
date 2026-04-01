<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalSaldoBeranda = document.getElementById('totalSaldoBeranda');
        const totalPemasukanBeranda = document.getElementById('totalPemasukanBeranda');
        const totalPengeluaranBeranda = document.getElementById('totalPengeluaranBeranda');
        const pemasukanPercentageElement = document.getElementById('pemasukanPercentage');
        const pengeluaranPercentageElement = document.getElementById('pengeluaranPercentage');

        // Fungsi untuk format angka ke mata uang Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        // Dummy data awal
        let dummyData = {
            totalSaldo: 0,
            pemasukan: 0,
            pengeluaran: 0,
        };

        // Update UI dengan data dummy awal
        updateUI();

        function updateUI() {
            totalSaldoBeranda.innerText = formatRupiah(dummyData.totalSaldo);
            totalPemasukanBeranda.innerText = formatRupiah(dummyData.pemasukan);
            totalPengeluaranBeranda.innerText = formatRupiah(dummyData.pengeluaran);

            let total = dummyData.pemasukan + dummyData.pengeluaran;
            let pemasukanPercentage = total > 0 ? (dummyData.pemasukan / total) * 100 : 0;
            let pengeluaranPercentage = total > 0 ? (dummyData.pengeluaran / total) * 100 : 0;

            pemasukanPercentageElement.innerText = pemasukanPercentage.toFixed(0) + '%';
            pengeluaranPercentageElement.innerText = pengeluaranPercentage.toFixed(0) + '%';

            // Update pie chart
            pieChart.data.datasets[0].data = [pemasukanPercentage, pengeluaranPercentage];
            pieChart.update();
        }

        // Inisialisasi Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pemasukan',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#4ade80',
                    backgroundColor: 'rgba(74, 222, 128, 0.2)',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Pengeluaran',
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    borderColor: '#f87171',
                    backgroundColor: 'rgba(248, 113, 113, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Masuk', 'Keluar'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: ['#4ade80', '#f87171']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Event listener untuk form transaksi
        const form = document.getElementById('addTransactionForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Ambil nilai dari form
            const jenis = document.getElementById('jenisTransaksi').value;
            const jumlah = parseFloat(document.getElementById('jumlah').value);
            // Anda bisa mengambil nilai lain seperti tanggal, kategori, dll.

            // Update data dummy
            if (jenis === 'pemasukan') {
                dummyData.pemasukan += jumlah;
                dummyData.totalSaldo += jumlah;
            } else if (jenis === 'pengeluaran') {
                dummyData.pengeluaran += jumlah;
                dummyData.totalSaldo -= jumlah;
            }

            // Update UI
            updateUI();

            // Tutup modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addTransactionModal'));
            modal.hide();

            // Reset form
            form.reset();
        });
    });
</script>