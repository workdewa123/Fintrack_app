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
        Schema::table('penggunas', function (Blueprint $table) {
            // Menambahkan kolom foto_profil setelah kolom password
            $table->string('foto_profil')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('penggunas', function (Blueprint $table) {
            $table->dropColumn('foto_profil');
        });
    }
};
