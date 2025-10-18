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
        // Получаем бронирования без загрузки room (т.к. rooms нет в БД)
        $bookings = Booking::whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->get();
        
        return view('admin', compact('bookings'));
    }

    /**
     * Подтверждение бронирования
     */
    public function confirm($id)
    {
        return $this->updateBookingStatus($id, 'confirmed');
    }

    /**
     * Отмена бронирования
     */
    public function cancel($id)
    {
        return $this->updateBookingStatus($id, 'cancelled');
    }

    /**
     * Общий метод для обновления статуса бронирования
     */
    protected function updateBookingStatus($id, $status)
    {
        if (!in_array($status, ['confirmed', 'cancelled'])) {
            return back()->with('error', 'Неверный статус');
        }

        $booking = Booking::find($id);
        
        if (!$booking) {
            return back()->with('error', 'Бронирование не найдено');
        }

        $booking->update(['status' => $status]);

        $message = $status === 'confirmed' 
            ? 'Бронирование подтверждено!' 
            : 'Бронирование отменено!';

        return back()->with('success', $message);
    }

    /**
     * Старый метод (оставлен для обратной совместимости, если нужно)
     */
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