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
        Schema::create('kategoris', function (Blueprint $table) {
            $table->bigIncrements('id_kategori'); // BIGINT UNSIGNED
            $table->unsignedBigInteger('id_pengguna');
            $table->string('nama_kategori', 100);
            $table->enum('tipe', ['MASUK', 'KELUAR']);
            $table->timestamps();

            $table->foreign('id_pengguna')
                ->references('id_pengguna')->on('penggunas')
                ->onDelete('cascade');
        });    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
