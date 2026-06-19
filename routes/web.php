<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;

// Import Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Models\Portfolio;

// --- SISI CLIENT (PUBLIK) ---
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// --- SISTEM BOOKING ---
Route::get('/booking/{category_id}', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/api/check-availability', [BookingController::class, 'checkAvailability']);
Route::get('/booking/calendar/{category_id}', [BookingController::class, 'calendar'])->name('booking.calendar');

// --- SISTEM PEMBAYARAN MIDTRANS ---
// Ringkasan Pembayaran
Route::get('/booking/{id}/summary', [PaymentController::class, 'summary'])->name('payment.summary');

// Ambil Token Snap (Pastikan nama route ini sesuai dengan yang dipanggil di JavaScript)
Route::post('/payment/token', [PaymentController::class, 'getToken'])->name('payment.token');

// Halaman Sukses & Struk (Sesuaikan ID)
Route::get('/booking/success/{id}', [PaymentController::class, 'showSuccess'])->name('booking.success');
Route::get('/booking/receipt/{id}', [PaymentController::class, 'showReceipt'])->name('booking.receipt');

// Webhook Midtrans (Jangan lupa kecualikan di CSRF)
Route::post('/payment/callback', [PaymentController::class, 'callback']);

// --- SISI ADMIN (DENGAN PREFIX & AUTH) ---
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Dinamis (Sekarang menggunakan Controller, bukan closure manual)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengelolaan Resource
    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
    
    // Pengelolaan Pesanan & Laporan
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::get('/orders/report', [OrderController::class, 'downloadReport'])->name('orders.report');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    
    // Pengelolaan Jadwal
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules/manual', [ScheduleController::class, 'storeManual'])->name('schedules.manual');

    // Profile Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/schedules/monthly', [DashboardController::class, 'monthlySchedule'])->name('schedules.monthly');

    // Rute untuk upload/update gambar kategori (Portfolio)
    Route::post('/categories/update-image', [App\Http\Controllers\Admin\CategoryController::class, 'updateImage'])->name('categories.update_image');
    
    // Pastikan resource categories juga ada
    Route::delete('/portfolios/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroyPortfolio'])->name('portfolios.destroy');
    Route::patch('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    Route::post('/schedules/toggle-holiday', [ScheduleController::class, 'toggleHoliday'])->name('schedules.toggle_holiday');
    // Route::get('/test-reminder', [DashboardController::class, 'testReminder'])->name('test.reminder');
    //midtrans schedule
    Route::post('/schedules/prepare-payment', [ScheduleController::class, 'preparePayment'])->name('schedules.prepare');

    Route::post('/profile/photo', [ScheduleController::class, 'updateProfilePhoto'])
         ->name('profile.update_photo');

    // Route untuk Manajemen Bundling
    Route::post('/bundlings', [ScheduleController::class, 'storeBundling'])->name('bundlings.store');
    Route::delete('/bundlings/{id}', [ScheduleController::class, 'destroyBundling'])->name('bundlings.destroy');

    // routes/web.php (Pastikan di dalam group admin)
    Route::put('/bundlings/{id}', [ScheduleController::class, 'updateBundling'])->name('bundlings.update');

    Route::post('/categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.update_order');
    Route::post('/kebayas', [CategoryController::class, 'storeKebaya'])->name('kebayas.store');
    Route::delete('/kebayas/{id}', [CategoryController::class, 'destroyKebaya'])->name('kebayas.destroy');
    Route::patch('/profile/full-update', [ScheduleController::class, 'updateAllSettings'])->name('profile.full_update');
    Route::put('/portfolios/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'updatePortfolio'])->name('admin.portfolios.update');
    Route::put('/kebayas/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'updateKebayaData'])->name('admin.kebayas.update');
});

// Redirect Breeze Default Dashboard ke Admin Dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- AUTH / PASSWORD RESET ---
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
// routes/web.php
Route::get('/booking/promo/{bundling_id}', [BookingController::class, 'createFromPromo'])->name('booking.promo');



//test
Route::get('/test-midtrans', function() {
    $serverKey = env('MIDTRANS_SERVER_KEY');
    $url = "https://app.sandbox.midtrans.com/snap/v1/transactions";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // PAKSA MATIKAN SSL DI SINI
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode($serverKey . ':')
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'transaction_details' => ['order_id' => 'TEST-'.time(), 'gross_amount' => 10000]
    ]));

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) return "CURL Error: " . $err;
    return json_decode($response);
});





require __DIR__.'/auth.php';