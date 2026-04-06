@extends('layout.app')

@section('content')
<div class="regular-payment-container p-4">
    <div class="form-card mx-auto" style="background-color: #e6f0ff; border-radius: 1.5rem; padding: 2rem;">
        <h4 class="text-center fw-bold mb-4">Buat Pengingat</h4>

        <form action="{{ route('pembayaran.store') }}" method="POST">
            @csrf
            <div class="row mb-4 justify-content-center">
                <div class="col-md-auto">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe" id="pemasukanRadio" value="Pemasukan" checked>
                        <label class="form-check-label" for="pemasukanRadio">Pemasukan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe" id="pengeluaranRadio" value="Pengeluaran">
                        <label class="form-check-label" for="pengeluaranRadio">Pengeluaran</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Frekuensi Pengingat</label>
                        <select name="frekuensi" class="form-select">
                            <option value="HARIAN">Harian</option>
                            <option value="MINGGUAN">Mingguan</option>
                            <option value="BULANAN">Bulanan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rekening</label>
                        <select class="form-select" name="id_rekening" required>
                            <option value="">Pilih Rekening</option>
                            @foreach($rekenings as $rek)
                            <option value="{{ $rek->id }}">{{ $rek->nama_rekening }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Pembayaran</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="0" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Pembayaran</label>
                        <input type="text" name="nama_pembayaran" class="form-control" placeholder="Mis. Tagihan Listrik" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="id_kategori">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kat)
                            <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Komentar / Catatan</label>
                        <textarea name="komentar" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary px-5">Masuk</button>
                <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-danger px-5">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection