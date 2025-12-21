<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffBookingController;

use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// --- GROUP ROUTE STAFF ---
Route::prefix('staff')
    ->name('staff.')
    // ->middleware(['auth'])  <-- SAYA KOMENTAR/MATIKAN DULU SUPAYA TIDAK ERROR LOGIN
    ->group(function () {
    
    // 1. Halaman Utama (Jadwal)
    Route::get('/jadwal', [StaffBookingController::class, 'index'])->name('jadwal');
    
    // 2. FITUR CRUD (Create, Read, Update, Delete)
    Route::get('/create', [StaffBookingController::class, 'create'])->name('create');
    Route::post('/store', [StaffBookingController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [StaffBookingController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [StaffBookingController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [StaffBookingController::class, 'destroy'])->name('destroy');

    // 3. OPERASIONAL
    Route::post('/action/{id}', [StaffBookingController::class, 'action'])->name('action');
    Route::put('/finish/{id}', [StaffBookingController::class, 'finish'])->name('finish');

    // 4. PELAPORAN (Riwayat & Struk)
    Route::get('/riwayat', [StaffBookingController::class, 'riwayat'])->name('riwayat');
    Route::get('/struk/{id}', [StaffBookingController::class, 'cetakStruk'])->name('struk');

});


Route::prefix('staff')->group(function () {
    Route::get('/jadwal', [StaffBookingController::class, 'index'])->name('staff.jadwal');
    
    // Route untuk Check-in dan Mulai (Action Cepat)
    Route::post('/jadwal/{id}/action', [StaffBookingController::class, 'quickAction'])->name('staff.action');
    
    // Route khusus untuk Selesai (Input Denda/Tambahan)
    Route::put('/jadwal/{id}/finish', [StaffBookingController::class, 'finish'])->name('staff.finish');
    Route::get('/jadwal/{id}/struk', [StaffBookingController::class, 'cetakStruk'])->name('staff.struk');
    Route::get('/riwayat', [StaffBookingController::class, 'riwayat'])->name('staff.riwayat');

});
