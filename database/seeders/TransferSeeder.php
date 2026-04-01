<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transfers')->insert([
            [
                'id_rekening_asal' => 2,
                'id_rekening_tujuan' => 1,
                'jumlah' => 250000,
                'tanggal_transfer' => now()->format('Y-m-d'), // Diubah di sini
                'keterangan' => 'Pindah saldo ke dompet cash',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
