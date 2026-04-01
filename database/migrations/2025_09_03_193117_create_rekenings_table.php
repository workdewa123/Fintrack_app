<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekenings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id_rekening');
            $table->unsignedBigInteger('id_pengguna');
            $table->string('nama_rekening', 100);
            $table->decimal('saldo', 15, 2)->default(0);
            $table->string('mata_uang', 10)->default('IDR');
            $table->string('warna', 20)->nullable();
            $table->string('icon', 100)->nullable(); // PERBAIKAN: Ubah dari 'ikon' menjadi 'icon'
            $table->timestamps();

            $table->foreign('id_pengguna')
                ->references('id_pengguna')->on('penggunas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekenings');
    }
};
