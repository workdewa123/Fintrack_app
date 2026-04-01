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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi'); // BIGINT UNSIGNED
            $table->unsignedBigInteger('id_rekening');
            $table->unsignedBigInteger('id_kategori'); // BIGINT UNSIGNED (wajib sama tipe)
            $table->enum('tipe', ['MASUK', 'KELUAR']);
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_transaksi');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_rekening')
                ->references('id_rekening')->on('rekenings')
                ->onDelete('cascade');

            $table->foreign('id_kategori')
                ->references('id_kategori')->on('kategoris') // pastikan nama tabel plural sama
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};