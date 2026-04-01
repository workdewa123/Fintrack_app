<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class transaksi extends Model
{
    //
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = ['id_rekening', 'id_kategori', 'jumlah', 'tanggal', 'deskripsi', 'tipe'];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'id_rekening', 'id_rekening');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}