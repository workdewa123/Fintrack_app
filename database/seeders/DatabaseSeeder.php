<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PenggunaSeeder;
use Database\Seeders\KategoriSeeder;
use Database\Seeders\RekeningSeeder;
use Database\Seeders\TransaksiSeeder;
use Database\Seeders\PengingatSeeder;
use Database\Seeders\TransferSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PenggunaSeeder::class, // Panggil seeder pengguna terlebih dahulu
            KategoriSeeder::class, // Kemudian kategori
            RekeningSeeder::class, // Kemudian rekening
            TransaksiSeeder::class, // Kemudian transaksi yang butuh pengguna/kategori
            PengingatSeeder::class,
            TransferSeeder::class,
        ]);
    }
}
