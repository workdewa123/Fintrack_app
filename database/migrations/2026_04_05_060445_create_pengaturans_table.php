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
        if (!Schema::hasTable('pengaturans')) {
            Schema::create('pengaturans', function (Blueprint $table) {
                $table->id();
                
                // PERBAIKAN: Menambahkan parameter kedua 'id_pengguna' 
                // agar Laravel tahu kolom apa yang dijadikan referensi.
                $table->foreignId('user_id')
                    ->constrained('penggunas', 'id_pengguna') 
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
