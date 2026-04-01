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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id('id_transfer');
            $table->unsignedBigInteger('id_rekening_asal');
            $table->unsignedBigInteger('id_rekening_tujuan');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal_transfer');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_rekening_asal')
                ->references('id_rekening')->on('rekenings')
                ->onDelete('cascade');

            $table->foreign('id_rekening_tujuan')
                ->references('id_rekening')->on('rekenings')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};