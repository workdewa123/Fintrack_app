<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Penting: Mengubah ini
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable // Penting: Mengubah ini
{
    use HasFactory, Notifiable;

    protected $table = 'penggunas';
    protected $primaryKey = 'id_pengguna';
    protected $fillable = ['nama', 'username', 'password'];

    // Menambahkan hidden field untuk password
    protected $hidden = [
        'password',
    ];

    public function rekenings()
    {
        return $this->hasMany(Rekening::class, 'id_pengguna', 'id_pengguna');
    }
}
