<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class AdminController extends Controller
{
    /**
     * Главная страница админ-панели - список всех бронирований
     */
    public function index()
    {
        // Получаем бронирования без загрузки room (т.к. rooms нет в БД)
        $bookings = Booking::whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->get();
        
        return view('admin', compact('bookings'));
    }

    public function updateStatus(Booking $booking, $status)
    {
        if (!in_array($status, ['confirmed', 'cancelled'])) {
            return back()->with('error', 'Неверный статус');
        }

        $booking->update(['status' => $status]);

        $message = $status === 'confirmed' 
            ? 'Бронирование подтверждено!' 
            : 'Бронирование отменено!';

        return back()->with('success', $message);
    }
}