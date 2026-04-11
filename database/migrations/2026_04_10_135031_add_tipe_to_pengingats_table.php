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
            // Menambahkan kolom tipe setelah kolom id_pengingat atau id_rekening
            $table->enum('tipe', ['Pemasukan', 'Pengeluaran'])->after('id_rekening')->default('Pengeluaran');
        });
    }

    public function down(): void
    {
        Schema::table('pengingats', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
