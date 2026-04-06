<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rekening extends Model
{
    use HasFactory;

    protected $table = 'rekenings';
    protected $primaryKey = 'id_rekening';

    protected $fillable = [
        'id_pengguna',
        'nama_rekening',
        'saldo',
        'mata_uang',
        'warna',
        'icon' // GANTI DARI 'ikon' MENJADI 'icon'
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_rekening', 'id_rekening');
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'id_rekening_asal', 'id_rekening');
    }

    public function pengingats()
    {
        return $this->hasMany(Pengingat::class, 'id_rekening', 'id_rekening');
    }
}
