@extends('layout.app')

@section('content')
<div class="container p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Riwayat Pembayaran Reguler</h3>
        <a href="{{ route('pembayaran.create') }}" class="btn btn-primary">+ Tambah Pengingat Baru</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Frekuensi</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengingats as $item)
                    <tr>
                        <td>{{ $item->nama_pembayaran }}</td>
                        <td>
                            <span class="badge {{ $item->tipe == 'Pemasukan' ? 'bg-success' : 'bg-danger' }}">
                                {{ $item->tipe }}
                            </span>
                        </td>
                        <td>{{ $item->frekuensi }}</td>
                        <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('pembayaran.destroy', $item->id_pengingat) }}" method="POST" onsubmit="return confirm('Hapus pengingat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada pengingat yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection