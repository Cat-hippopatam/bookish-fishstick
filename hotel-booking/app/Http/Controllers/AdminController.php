<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Главная страница админ-панели - список всех бронирований
     */
    public function index()
    {
        // Получаем все бронирования с связанными номерами
        // latest() - сортировка от новых к старым
        // with('room') - жадная загрузка связи (избегаем N+1 проблемы)
        $bookings = Booking::with('room')->latest()->get();
        
        return view('admin', compact('bookings'));
    }

    /**
     * Обновляет статус бронирования
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        // Валидируем что статус один из разрешенных
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        // Обновляем статус бронирования
        $booking->update(['status' => $request->status]);

        // Возвращаем обратно с сообщением об успехе
        return back()->with('success', 'Статус обновлен!');
    }
}