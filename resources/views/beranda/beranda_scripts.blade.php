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
                    datasets: [
                        { // PERBAIKAN: Posisi kurung kurawal di sini sebelumnya salah
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
                        legend: { display: true, position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        // --- 6. LOGIKA SIMPAN TRANSAKSI BARU ---
        const addForm = document.getElementById('addTransactionForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Mencegah reload halaman

                // Ambil data dari form
                const formData = {
                    id_rekening: document.getElementById('rekening').value,
                    id_kategori: document.getElementById('kategori').value,
                    tipe: document.getElementById('jenisTransaksi').value === 'pemasukan' ? 'MASUK' : 'KELUAR',
                    jumlah: document.getElementById('jumlah').value,
                    tanggal_transaksi: document.getElementById('tanggalWaktu').value,
                    catatan: document.getElementById('catatan').value,
                };

                fetch('/api/transaksi-simpan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal menyimpan transaksi');
                    return res.json();
                })
                .then(data => {
                    // Tutup modal
                    const modalElem = document.getElementById('addTransactionModal');
                    const modal = bootstrap.Modal.getInstance(modalElem);
                    if (modal) modal.hide();

                    // Reset form
                    addForm.reset();

                    alert('Transaksi berhasil disimpan!');
                    
                    // Refresh data di beranda (angka & grafik) tanpa reload halaman
                    fetchDashboardData(); 
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menyimpan.');
                });
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
                        backgroundColor: ['#4ade80', '#f87171'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '80%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
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

        // --- 3. FUNGSI AMBIL DATA DASHBOARD ---
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

                    // Hitung Persentase
                    let total = parseFloat(data.totalPemasukan) + parseFloat(data.totalPengeluaran);
                    let inPct = total > 0 ? Math.round((data.totalPemasukan / total) * 100) : 0;
                    let outPct = total > 0 ? 100 - inPct : 0;

                    if (document.getElementById('pemasukanPercentage'))
                        document.getElementById('pemasukanPercentage').innerText = inPct + '%';
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
                })
                .catch(err => console.error("Gagal memuat data dashboard:", err));
        };

        // --- 4. LOAD REKENING KE DROPDOWN MODAL ---
        function loadRekeningToModal() {
            fetch('/rekening-data')
                .then(res => res.json())
                .then(response => {
                    const select = document.getElementById('rekening');
                    if (select) {
                        select.innerHTML = '<option disabled selected>Pilih Rekening</option>';
                        // Karena /rekening-data menggunakan paginate, data ada di response.data
                        const dataRekening = response.data || response; 
                        dataRekening.forEach(r => {
                            select.innerHTML += `<option value="${r.id_rekening}">${r.nama_rekening} (${formatRupiah(r.saldo)})</option>`;
                        });
                    }
                });
        }

        fetchDashboardData();
        loadRekeningToModal();

        // --- 5. LOAD KATEGORI KE DROPDOWN MODAL ---
function loadKategoriToModal() {
    // Sesuaikan URL ini dengan route getKategoriData di web.php
    fetch('/kategori/kategori-data') 
        .then(res => res.json())
        .then(response => {
            const select = document.getElementById('kategori');
            if (select) {
                select.innerHTML = '<option disabled selected>Pilih Kategori</option>';
                // Mengambil data dari response.data karena menggunakan pagination
                const dataKategori = response.data || response; 
                dataKategori.forEach(k => {
                    select.innerHTML += `<option value="${k.id_kategori}">${k.nama_kategori} (${k.tipe})</option>`;
                });
            }
        })
        .catch(err => console.error("Gagal memuat kategori:", err));
}

// Panggil fungsinya di bagian bawah script
loadKategoriToModal();
    });
</script>