<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('rekenings')->insert([
            [
                'id_pengguna' => 1,
                'nama_rekening' => 'Dompet Cash',
                'saldo' => 500000,
                'mata_uang' => 'IDR',
                'warna' => '#4CAF50',
                'icon' => 'wallet.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 1,
                'nama_rekening' => 'Bank BCA',
                'saldo' => 2000000,
                'mata_uang' => 'IDR',
                'warna' => '#2196F3',
                'icon' => 'bca.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}