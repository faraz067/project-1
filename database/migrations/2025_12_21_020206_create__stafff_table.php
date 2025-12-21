<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_booking', function (Blueprint $table) {
            $table->id();

            // Data pelanggan
            $table->string('nama_pelanggan');
            $table->string('no_hp', 20);

            // Jadwal
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            // Operasional staff
            $table->boolean('konfirmasi_datang')->default(false);
            $table->enum('status_booking', ['booking','aktif','selesai','batal'])
                  ->default('booking');

            // Tambahan
            $table->integer('jam_tambahan')->nullable();
            $table->integer('denda')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_booking');
    }
};
