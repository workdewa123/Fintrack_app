<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('kategoris')->insert([
            [
                'id_pengguna' => 1,
                'nama_kategori' => 'Gaji',
                'tipe' => 'MASUK',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 1,
                'nama_kategori' => 'Makan',
                'tipe' => 'KELUAR',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}