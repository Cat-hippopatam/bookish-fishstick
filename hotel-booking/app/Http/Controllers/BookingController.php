<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\HotelDataService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected $hotelDataService;

    public function __construct(HotelDataService $hotelDataService)
    {
        $this->hotelDataService = $hotelDataService;
    }

    /**
     * Показывает форму бронирования для конкретного номера
     */
    // public function show($roomId)
    // {
    //     $room = $this->hotelDataService->getRoomWithRelations($roomId);
        
    //     if (!$room) {
    //         abort(404, 'Номер не найден');
    //     }

    //     return view('booking', compact('room'));
    // }
    // public function show($roomId)
    // {
    //     // Добавляем отладку
    //     \Log::info("BookingController: show method called with roomId: " . $roomId);
        
    //     // Получаем комнату из JSON данных
    //     $room = $this->hotelDataService->getRoomWithRelations($roomId);
        
    //     \Log::info("Room data: " . json_encode($room ?: 'NOT FOUND'));
        
    //     if (!$room) {
    //         \Log::error("Room not found for ID: " . $roomId);
    //         abort(404, 'Номер не найден');
    //     }

    //     return view('booking', compact('room'));
    // }

    public function show($roomNumber)
    {
        \Log::info("BookingController: show method called with roomNumber: " . $roomNumber);
        
        // Получаем комнату по номеру комнаты
        $room = $this->hotelDataService->getRoomByNumber($roomNumber);
        
        \Log::info("Room data: " . json_encode($room ?: 'NOT FOUND'));
        
        if (!$room) {
            \Log::error("Room not found for number: " . $roomNumber);
            abort(404, 'Номер не найден');
        }

        return view('booking', compact('room'));
    }


    /**
     * Сохраняет новое бронирование в базу
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|numeric',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'client_phone' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        // Проверяем, существует ли комната в JSON
        $room = $this->hotelDataService->getRoomWithRelations($validated['room_id']);
        if (!$room) {
            return back()->with('error', 'Выбранный номер не существует');
        }

        // Проверяем доступность номера
        if (!$room['is_available']) {
            return back()->with('error', 'К сожалению, этот номер сейчас недоступен для бронирования');
        }

        // Проверяем пересечение дат с существующими бронированиями
        $isDatesAvailable = $this->checkDateAvailability(
            $validated['room_id'], 
            $validated['check_in'], 
            $validated['check_out']
        );

        if (!$isDatesAvailable) {
            return back()->with('error', 'На выбранные даты номер уже забронирован. Пожалуйста, выберите другие даты.');
        }

        // Создаем запись бронирования в БД
        Booking::create($validated);

        return redirect()->route('home')->with('success', 'Бронирование успешно создано! Мы свяжемся с вами для подтверждения.');
    }

    /**
     * Проверяет доступность номера на выбранные даты
     */
    protected function checkDateAvailability($roomId, $checkIn, $checkOut)
    {
        // Ищем активные бронирования для этого номера (подтвержденные и ожидающие)
        $existingBookings = Booking::where('room_id', $roomId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $newCheckIn = Carbon::parse($checkIn);
        $newCheckOut = Carbon::parse($checkOut);

        foreach ($existingBookings as $booking) {
            $existingCheckIn = Carbon::parse($booking->check_in);
            $existingCheckOut = Carbon::parse($booking->check_out);

            // Проверяем пересечение дат
            if ($this->datesOverlap($newCheckIn, $newCheckOut, $existingCheckIn, $existingCheckOut)) {
                return false; // Нашли пересечение - даты недоступны
            }
        }

        return true; // Пересечений нет - даты доступны
    }

    /**
     * Проверяет пересечение двух интервалов дат
     */
    protected function datesOverlap($start1, $end1, $start2, $end2)
    {
        // Пересечение происходит если:
        // - начало нового бронирования внутри существующего интервала
        // - конец нового бронирования внутри существующего интервала  
        // - новое бронирование полностью содержит существующее
        // - существующее бронирование полностью содержит новое
        return $start1->lt($end2) && $end1->gt($start2);
    }
}