<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffBookingController;

// 1. PENGAMANAN HALAMAN DEPAN & LOGIN
Route::get('/', function () { return redirect()->route('staff.jadwal'); });
Route::get('/login', function () { return redirect()->route('staff.jadwal'); })->name('login');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


// --- GROUP ROUTE STAFF (SUPORT SEMUA METODE: GET, POST, PUT) ---
Route::prefix('staff')
    ->name('staff.')      
    // ->middleware(['auth']) // Matikan dulu auth biar lancar
    ->group(function () {
    
    // 1. JADWAL
    Route::get('/jadwal', [StaffBookingController::class, 'index'])->name('jadwal');
    
    // 2. CRUD BOOKING
    Route::get('/create', [StaffBookingController::class, 'create'])->name('create');
    Route::post('/store', [StaffBookingController::class, 'store'])->name('store');
    
    Route::get('/edit/{id}', [StaffBookingController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [StaffBookingController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [StaffBookingController::class, 'destroy'])->name('destroy');

    // 3. OPERASIONAL (CHECK-IN / START)
    // Kita terima GET (link) dan POST (tombol form)
    Route::match(['get', 'post'], '/action/{id}', [StaffBookingController::class, 'action'])->name('action');
    
    // 4. SELESAI / FINISH (FIX ERROR 405 DISINI)
    // Kita terima GET, POST, dan PUT sekaligus. Jadi formulir tipe apapun masuk!
    Route::match(['get', 'post', 'put'], '/finish/{id}', [StaffBookingController::class, 'finish'])->name('finish');

    // 5. PELAPORAN
    Route::get('/riwayat', [StaffBookingController::class, 'riwayat'])->name('riwayat');
    Route::get('/struk/{id}', [StaffBookingController::class, 'cetakStruk'])->name('struk');

});

// --- JALUR CADANGAN (Supaya tidak ada error "Route not defined" di tampilan lama) ---
Route::get('/staff/booking/create', [StaffBookingController::class, 'create'])->name('booking.create');
Route::post('/staff/booking/store', [StaffBookingController::class, 'store'])->name('booking.store');
Route::get('/staff/booking/detail/{id}', [StaffBookingController::class, 'cetakStruk'])->name('booking.detail');