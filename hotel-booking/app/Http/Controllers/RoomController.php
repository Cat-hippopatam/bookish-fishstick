<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HotelDataService;

class RoomController extends Controller
{
    protected $hotelDataService;

    public function __construct(HotelDataService $hotelDataService)
    {
        $this->hotelDataService = $hotelDataService;
    }

    // public function index(Request $request)
    // {
    //     $resetRequested = $request->has('reset');
        
    //     // Получаем все комнаты из JSON
    //     $rooms = $this->hotelDataService->getRoomsWithRelations();
    //     $categories = $this->hotelDataService->getCategories();
        
    //     // Случайный порядок при сбросе или обычной загрузке
    //     if ($resetRequested || !$request->has('category')) {
    //         if ($rooms->count() > 4) {
    //             $randomRooms = $rooms->shuffle();
    //             $firstFour = $randomRooms->take(4);
    //             $rest = $randomRooms->slice(4);
    //             $rooms = $firstFour->concat($rest);
    //         } else {
    //             $rooms = $rooms->shuffle();
    //         }
    //         $isRandomOrder = true;
    //     } else {
    //         $isRandomOrder = false;
    //     }
        
    //     return view('index', compact('rooms', 'isRandomOrder', 'categories'));
    // }

    public function index(Request $request)
    {
        $resetRequested = $request->has('reset');
        $categoryFilter = $request->get('category');
        
        // Получаем все комнаты из JSON
        $rooms = $this->hotelDataService->getRoomsWithRelations();
        $categories = $this->hotelDataService->getCategories();
        
        // Случайный порядок только при сбросе
        if ($resetRequested) {
            if ($rooms->count() > 4) {
                $randomRooms = $rooms->shuffle();
                $firstFour = $randomRooms->take(4);
                $rest = $randomRooms->slice(4);
                $rooms = $firstFour->concat($rest);
            } else {
                $rooms = $rooms->shuffle();
            }
            $isRandomOrder = true;
        } else {
            $isRandomOrder = false;
            
            // Фильтрация по категории из URL
            if ($categoryFilter) {
                $rooms = $rooms->filter(function($room) use ($categoryFilter) {
                    return $room['category_id'] == $categoryFilter;
                });
            }
        }
        
        return view('index', compact('rooms', 'isRandomOrder', 'categories'));
    }

}