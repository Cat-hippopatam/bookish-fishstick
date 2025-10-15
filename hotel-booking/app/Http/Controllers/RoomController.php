<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Главная страница - список всех номеров
     * При сбросе фильтров первые 4 карточки отображаются случайным образом
     */
    public function index(Request $request)
    {
        // Проверяем, был ли запрос на сброс фильтров
        $resetRequested = $request->has('reset');
        
        if ($resetRequested) {
            // При сбросе фильтров - случайный порядок первых 4 карточек
            $allRooms = Room::all();
            
            if ($allRooms->count() > 4) {
                // Перемешиваем все номера и берем первые 4 как случайные
                $shuffledRooms = $allRooms->shuffle();
                $randomRooms = $shuffledRooms->take(4);
                $remainingRooms = $shuffledRooms->slice(4);
                
                // Объединяем
                $rooms = $randomRooms->concat($remainingRooms);
            } else {
                $rooms = $allRooms;
            }
            
            $isRandomOrder = true;
        } else {
            // Обычная загрузка - стандартный порядок
            $rooms = Room::all();
            $isRandomOrder = false;
        }
        
        return view('index', compact('rooms', 'isRandomOrder'));
    }
}