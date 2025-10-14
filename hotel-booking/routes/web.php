<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Здесь вы можете зарегистрировать веб-маршруты для вашего приложения.
|
*/

// 1. Главная страница - список номеров
Route::get('/', [RoomController::class, 'index'])->name('home');

// 2. Маршруты для бронирования
Route::get('/booking/{room}', [BookingController::class, 'show'])->name('booking.show');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// 3. Маршруты для аутентификации администратора
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);

// 4. Группа маршрутов для админ-панели (требуют аутентификации)
Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/bookings/{booking}/update-status', [AdminController::class, 'updateStatus'])->name('admin.bookings.update-status');
});