<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengingat extends Model
{
    use HasFactory;

    protected $table = 'pengingats';
    protected $primaryKey = 'id_pengingat';

    protected $fillable = [
        'id_rekening',
        'id_kategori',
        'nama_pembayaran',
        'frekuensi',
        'tanggal_mulai',
        'tanggal_akhir',
        'jumlah',
        'komentar',
        'tipe' // Tambahkan tipe untuk membedakan Pemasukan/Pengeluaran
    ];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'id_rekening', 'id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}
