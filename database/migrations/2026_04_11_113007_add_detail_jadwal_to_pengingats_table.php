<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengingats', function (Blueprint $table) {
            // Kolom ini akan menyimpan angka 1-7 (Mingguan) atau 1-31 (Bulanan)
            $table->integer('detail_jadwal')->nullable()->after('frekuensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengingats', function (Blueprint $table) {
            //
        });
    }
};
