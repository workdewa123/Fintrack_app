<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    // Tambahkan user_id di sini!
    protected $fillable = [
        'user_id',
        'mata_uang',
        'format_tanggal',
        'bahasa',
        'notifikasi_aktif'
    ];
}
