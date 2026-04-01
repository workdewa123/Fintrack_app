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
        Schema::create('pengingats', function (Blueprint $table) {
            $table->bigIncrements('id_pengingat');
            $table->unsignedBigInteger('id_rekening');
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->string('nama_pembayaran', 100);
            $table->enum('frekuensi', ['HARIAN', 'MINGGUAN', 'BULANAN']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->string('komentar')->nullable();
            $table->timestamps();
            
            $table->foreign('id_rekening')
            ->references('id_rekening')->on('rekenings')
            ->onDelete('cascade');
            
            $table->foreign('id_kategori')
             ->references('id_kategori')->on('kategoris')
             ->onDelete('set null');
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengingats');
    }
};