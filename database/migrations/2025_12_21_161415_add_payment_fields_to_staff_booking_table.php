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
        Schema::table('staff_booking', function (Blueprint $table) {
            // 1. Kolom DP (Uang Muka) - Integer, Default 0
            // Saya taruh 'nullable' untuk jaga-jaga, tapi default 0 lebih aman
            $table->integer('dp')->default(0)->after('jam_selesai');

            // 2. Kolom Status Pembayaran - String (Lunas / DP / Belum Bayar)
            $table->string('status_pembayaran')->default('Belum Bayar')->after('dp');

            // 3. Kolom Diskon - Integer, Default 0
            $table->integer('diskon')->default(0)->after('status_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_booking', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi dibatalkan (rollback)
            $table->dropColumn(['dp', 'status_pembayaran', 'diskon']);
        });
    }
};
