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
        // Ubah 'Table $table' menjadi 'Blueprint $table'
        Schema::table('rekenings', function (Blueprint $table) {
            $table->dropColumn('mata_uang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekenings', function (Blueprint $table) {
            $table->string('mata_uang')->default('IDR');
        });
    }
};
