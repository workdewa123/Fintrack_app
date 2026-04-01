<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('transaksis')->insert([
            [
                'id_rekening' => 1,
                'id_kategori' => 1,
                'tipe' => 'MASUK',
                'jumlah' => 5000000,
                'tanggal_transaksi' => now()->toDateString(),
                'keterangan' => 'Gaji bulanan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_rekening' => 1,
                'id_kategori' => 2,
                'tipe' => 'KELUAR',
                'jumlah' => 75000,
                'tanggal_transaksi' => now()->toDateString(),
                'keterangan' => 'Makan siang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}