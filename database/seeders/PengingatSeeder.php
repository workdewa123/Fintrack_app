<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengingatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('pengingats')->insert([
            [
                'id_rekening' => 2,
                'id_kategori' => 2,
                'nama_pembayaran' => 'Bayar Langganan Netflix',
                'frekuensi' => 'BULANAN',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_akhir' => null,
                'jumlah' => 150000,
                'komentar' => 'Otomatis setiap bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}