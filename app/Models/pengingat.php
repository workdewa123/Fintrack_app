<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class pengingat extends Model
{
    //
    use HasFactory;

    protected $table = 'pengingats';
    protected $primaryKey = 'id_pengingat';
    protected $fillable = ['id_rekening', 'id_kategori', 'nama_pembayaran', 'frekuensi', 'tanggal_mulai', 'tanggal_akhir', 'jumlah', 'komentar'];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'id_rekening', 'id_rekening');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}