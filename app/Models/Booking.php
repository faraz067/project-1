<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan yang ada di database kamu
    protected $table = 'staff_booking'; 

    // --- BAGIAN INI YANG KITA PERBAIKI ---
    // Semua kolom yang mau diisi lewat formulir HARUS masuk sini
    protected $fillable = [
        'nama_pelanggan',   // <--- Wajib ada agar tidak error saat Create
        'no_hp',            // <--- Wajib ada
        'tanggal',          // <--- Wajib ada
        'jam_mulai',        // <--- Wajib ada
        'jam_selesai',      // <--- Wajib ada
        'konfirmasi_datang',
        'status_booking',
        'jam_tambahan',
        'denda',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'konfirmasi_datang' => 'boolean',
    ];
}