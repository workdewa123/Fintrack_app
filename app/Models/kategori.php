<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class kategori extends Model
{
    //
    use HasFactory;

    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['id_pengguna', 'nama_kategori', 'tipe'];
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_kategori', 'id_kategori');
    }

    public function pengingats()
    {
        return $this->hasMany(Pengingat::class, 'id_kategori', 'id_kategori');
    }
}