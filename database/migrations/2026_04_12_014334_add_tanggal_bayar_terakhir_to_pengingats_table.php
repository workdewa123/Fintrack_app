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
            $table->date('tanggal_bayar_terakhir')->nullable()->after('jumlah');
        });
    }

    public function down(): void
    {
        Schema::table('pengingats', function (Blueprint $table) {
            $table->dropColumn('tanggal_bayar_terakhir');
        });
    }
};
