<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// 1. Главная страница - список номеров
Route::get('/', [RoomController::class, 'index'])->name('home');

// 2. Маршруты для бронирования - используем room_number для показа, но id для данных
Route::get('/booking/{roomNumber}', [BookingController::class, 'show'])->name('booking.show');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
// 2. Маршруты для бронирования (ИСПРАВЛЯЕМ - убираем привязку модели)
// Route::get('/booking/{room}', [BookingController::class, 'show'])->name('booking.show');
// Используем room_number вместо id
// Route::get('/booking/{roomNumber}', [BookingController::class, 'show'])->name('booking.show');
// Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// 3. Маршруты для аутентификации администратора
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);

// 4. Группа маршрутов для админ-панели (требуют аутентификации)
Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/bookings/{booking}/confirm', function ($booking) {
        return app(AdminController::class)->updateStatus($booking, 'confirmed');
    })->name('admin.bookings.confirm');
    
    Route::post('/admin/bookings/{booking}/cancel', function ($booking) {
        return app(AdminController::class)->updateStatus($booking, 'cancelled');
    })->name('admin.bookings.cancel');
});

// Отладочный маршрут для проверки данных
Route::get('/debug-rooms', function() {
    $service = app(App\Services\HotelDataService::class);
    
    echo "<h1>Debug Rooms Data</h1>";
    
    $rooms = $service->getRoomsWithRelations();
    
    echo "<h3>All Rooms from JSON:</h3>";
    foreach ($rooms as $room) {
        echo "ID: " . $room['id'] . " (" . gettype($room['id']) . ") | ";
        echo "Number: " . $room['room_number'] . " | ";
        echo "Category: " . $room['category']['name'] . "<br>";
    }
    
    echo "<h3>Test Room Lookup:</h3>";
    $testIds = [101, '101', 102, '102'];
    foreach ($testIds as $testId) {
        $room = $service->getRoomWithRelations($testId);
        echo "Lookup ID: '{$testId}' (" . gettype($testId) . ") → ";
        echo $room ? "FOUND: " . $room['room_number'] : "NOT FOUND";
        echo "<br>";
    }
});