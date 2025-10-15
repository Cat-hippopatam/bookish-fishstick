<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Главная страница админ-панели - список всех бронирований
     * Показываем только pending и confirmed, скрываем cancelled
     */
    public function index()
    {
        // Получаем бронирования со статусами pending и confirmed
        $bookings = Booking::with('room')
            ->whereIn('status', ['pending', 'confirmed'])
            ->latest()
            ->get();
        
        return view('admin', compact('bookings'));
    }

    /**
     * Обновляет статус бронирования
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        // Обновляем статус бронирования
        $booking->update(['status' => $request->status]);

        // Если статус cancelled, не показываем в списке
        if ($request->status === 'cancelled') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Бронирование отменено и скрыто из списка!');
        }

        return back()->with('success', 'Статус бронирования обновлен!');
    }
}