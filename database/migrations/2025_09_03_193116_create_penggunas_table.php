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
        // penggunas
        Schema::create('penggunas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('id_pengguna')->autoIncrement();
            $table->string('nama', 100);
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunas');
    }
};