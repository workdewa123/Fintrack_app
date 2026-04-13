<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Fintrack</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-success { color: green; }
        .text-danger { color: red; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Transaksi Keuangan</h2>
        <p>Dicetak pada: {{ now()->format('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Rekening</th>
                <th>Tipe</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $item)
            <tr>
                <td>{{ date('d-m-Y', strtotime($item->tanggal_transaksi)) }}</td>
                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $item->rekening->nama_rekening }}</td>
                <td class="{{ $item->tipe == 'MASUK' ? 'text-success' : 'text-danger' }}">
                    {{ $item->tipe }}
                </td>
                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
