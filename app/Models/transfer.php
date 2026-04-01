<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $table = 'transfers';
    protected $primaryKey = 'id_transfer';

    protected $fillable = [
        'id_rekening_asal',
        'id_rekening_tujuan',
        'jumlah',
        'tanggal_transfer',
        'status'
    ];

    public function rekeningAsal()
    {
        return $this->belongsTo(Rekening::class, 'id_rekening_asal', 'id_rekening');
    }

    public function rekeningTujuan()
    {
        return $this->belongsTo(Rekening::class, 'id_rekening_tujuan', 'id_rekening');
    }
}
