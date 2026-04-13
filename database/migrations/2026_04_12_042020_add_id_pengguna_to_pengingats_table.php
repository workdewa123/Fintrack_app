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
            $table->unsignedBigInteger('id_pengguna')->after('id_pengingat')->nullable();
            
            $table->foreign('id_pengguna')
                ->references('id_pengguna')->on('penggunas')
                ->onDelete('cascade');
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
