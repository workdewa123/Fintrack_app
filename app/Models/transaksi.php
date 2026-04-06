<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';

    // PERBAIKAN: Nama kolom harus sama persis dengan database & input form
    protected $fillable = [
        'id_rekening', 
        'id_kategori', 
        'jumlah', 
        'tanggal_transaksi', // Sebelumnya 'tanggal'
        'keterangan',           
        'tipe'
    ];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'id_rekening', 'id_rekening');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}