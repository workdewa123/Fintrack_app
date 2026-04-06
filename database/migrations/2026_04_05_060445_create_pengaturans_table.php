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
        // Cek dulu apakah tabel sudah ada untuk menghindari error 1050
        if (!Schema::hasTable('pengaturans')) {
            Schema::create('pengaturans', function (Blueprint $table) {
                $table->id();
                // Pastikan tabel 'penggunas' sudah dimigrasi sebelum file ini dijalankan
                $table->foreignId('user_id')
                    ->constrained('penggunas')
                    ->onDelete('cascade');
                $table->string('mata_uang')->default('IDR-Rupiah');
                $table->string('format_tanggal')->default('DD/MM/YYYY');
                $table->string('bahasa')->default('Indonesia');
                $table->string('kunci_aplikasi_pin')->nullable();
                $table->boolean('notifikasi_aktif')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
