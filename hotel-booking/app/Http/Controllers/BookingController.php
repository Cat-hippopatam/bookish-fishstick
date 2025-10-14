<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Показывает форму бронирования для конкретного номера
     */
    public function show(Room $room)
    {
        // Типичный пример "Implicit Binding" - Laravel автоматически 
        // находит комнату по ID из URL
        return view('booking', compact('room'));
    }

    /**
     * Сохраняет новое бронирование в базу
     */
    public function store(Request $request)
    {
        // Валидация данных формы
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id', // проверяем что комната существует
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'client_phone' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in', // проверяем что дата выезда после заезда
        ]);

        // Создаем запись в базе данных
        Booking::create($validated);

        // Редирект с "flash-сообщением"
        return redirect()->route('home')->with('success', 'Бронирование успешно создано!');
    }
}