@extends('layout.app')

@section('content')

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .regular-payment-container {
        padding: 2rem;
    }

    .form-card {
        background-color: #e6f0ff;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-card h4 {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 0.75rem;
        border: 1px solid #d1d5db;
        padding: 0.75rem 1rem;
        box-shadow: none;
        background-color: #ffffff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }

    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .btn-submit {
        background-color: #2563eb;
        color: white;
        border-radius: 0.5rem;
        padding: 0.75rem 2.5rem;
        font-weight: 600;
    }

    .btn-cancel {
        background-color: transparent;
        color: #ef4444;
        border: 1px solid #ef4444;
        border-radius: 0.5rem;
        padding: 0.75rem 2.5rem;
        font-weight: 600;
    }
</style>

<div class="regular-payment-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Pembayaran Reguler</h3>
            <p class="text-muted">Atur Pengingat Pembayaran atau Pemasukan Rutin</p>
        </div>
        <div class="user-profile d-flex align-items-center gap-2">
            <div class="user-profile-icon" style="background-image: url('{{ asset('images/avatar-placeholder.png') }}'); background-size: cover; width:40px; height:40px; border-radius:50%;"></div>
        </div>
    </div>

    <div class="form-card mx-auto">
        <h4 class="text-center">Buat Pengingat</h4>
        <form>
            <div class="row mb-4 justify-content-center">
                <div class="col-md-auto">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenisTransaksi" id="pemasukanRadio" value="Pemasukan" checked>
                        <label class="form-check-label" for="pemasukanRadio">Pemasukan</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="jenisTransaksi" id="pengeluaranRadio" value="Pengeluaran">
                        <label class="form-check-label" for="pengeluaranRadio">Pengeluaran</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="frekuensi" class="form-label">Frekuensi Pengingat</label>
                        <input type="text" class="form-control" id="frekuensi" placeholder="Mis. Bulanan, Mingguan">
                    </div>
                    <div class="mb-3">
                        <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggalMulai">
                    </div>
                    <div class="mb-3">
                        <label for="rekening" class="form-label">Rekening</label>
                        <select class="form-select" id="rekening">
                            <option selected>Pilih Rekening</option>
                            <option value="1">Dana Utama</option>
                            <option value="2">BCA</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlahPembayaran" class="form-label">Jumlah Pembayaran</label>
                        <input type="text" class="form-control" id="jumlahPembayaran" placeholder="IDR 0">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="namaPembayaran" class="form-label">Nama Pembayaran</label>
                        <input type="text" class="form-control" id="namaPembayaran" placeholder="Mis. Gaji Bulanan, Tagihan Listrik">
                    </div>
                    <div class="mb-3">
                        <label for="tanggalAkhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="tanggalAkhir">
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori">
                            <option selected>Pilih Kategori</option>
                            <option value="1">Gaji</option>
                            <option value="2">Transportasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="komentar" class="form-label">Komentar / Catatan</label>
                        <textarea class="form-control" id="komentar" rows="3" placeholder="Tambahkan catatan jika perlu"></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-submit">Masuk</button>
                <button type="button" class="btn btn-cancel">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection