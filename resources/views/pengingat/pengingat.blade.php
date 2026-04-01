@extends('layout.app')

@section('content')

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
    }

    .reminder-container {
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

    .btn-submit {
        background-color: #2563eb;
        color: white;
        border-radius: 0.5rem;
        padding: 0.75rem 2.5rem;
        font-weight: 600;
    }

    .btn-cancel {
        background-color: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem 2.5rem;
        font-weight: 600;
    }
</style>

<div class="reminder-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Pengingat</h3>
            <p class="text-muted">Atur Pengingat Kegiatan atau Jadwal Penting Anda</p>
        </div>
        <div class="user-profile d-flex align-items-center gap-2">
            <div class="user-profile-icon" style="background-image: url('{{ asset('images/avatar-placeholder.png') }}'); background-size: cover; width:40px; height:40px; border-radius:50%;"></div>
        </div>
    </div>

    <div class="form-card mx-auto">
        <form>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="namaPengingat" class="form-label">Nama Pengingat</label>
                        {{-- Memasukkan nilai dari data JSON ke dalam input field --}}
                        <input type="text" class="form-control" id="namaPengingat" value="{{ $pengingat['nama_pembayaran'] }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="frekuensiPengingat" class="form-label">Frekuensi Pengingat</label>
                        {{-- Memasukkan nilai dari data JSON ke dalam input field --}}
                        <input type="text" class="form-control" id="frekuensiPengingat" value="{{ $pengingat['frekuensi'] }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggalMulai" class="form-label">Tanggal Mulai</label>
                        {{-- Memasukkan nilai dari data JSON ke dalam input field --}}
                        <input type="date" class="form-control" id="tanggalMulai" value="{{ \Carbon\Carbon::parse($pengingat['tanggal_mulai'])->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="waktu" class="form-label">Waktu</label>
                        {{-- Menggunakan waktu default karena tidak ada data waktu yang spesifik di JSON --}}
                        <input type="time" class="form-control" id="waktu" value="09:00">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="komentar" class="form-label">Komentar / Catatan</label>
                {{-- Memasukkan nilai dari data JSON ke dalam textarea --}}
                <textarea class="form-control" id="komentar" rows="3">{{ $pengingat['komentar'] }}</textarea>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="button" class="btn btn-cancel">Batal</button>
                <button type="submit" class="btn btn-submit">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection