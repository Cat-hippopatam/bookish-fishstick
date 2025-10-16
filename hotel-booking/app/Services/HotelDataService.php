<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class HotelDataService
{
    protected $dataFile = 'hotel_data.json';

    /**
     * Получить все данные из JSON
     */
    public function getAllData()
    {
        if (!Storage::exists($this->dataFile)) {
            $this->createDefaultData();
        }

        $content = Storage::get($this->dataFile);
        return json_decode($content, true);
    }

    /**
     * Получить все категории
     */
    public function getCategories()
    {
        $data = $this->getAllData();
        return collect($data['categories'] ?? []);
    }

    /**
     * Получить все удобства
     */
    public function getAmenities()
    {
        $data = $this->getAllData();
        return collect($data['amenities'] ?? []);
    }

    /**
     * Получить все комнаты с связанными данными
     */
    public function getRoomsWithRelations()
    {
        $data = $this->getAllData();
        $rooms = collect($data['rooms'] ?? []);
        $categories = $this->getCategories();
        $amenities = $this->getAmenities();

        return $rooms->map(function($room) use ($categories, $amenities) {
            $room['category'] = $categories->firstWhere('id', $room['category_id']);
            $room['amenities_list'] = $amenities->whereIn('id', $room['amenity_ids'])->values();
            return $room;
        });
    }

    /**
     * Получить комнату по ID с связанными данными
     */
    // public function getRoomWithRelations($roomId)
    // {
    //     return $this->getRoomsWithRelations()->firstWhere('id', $roomId);
    // }
    
    // public function getRoomWithRelations($roomId)
    // {
    //     $rooms = $this->getRoomsWithRelations();
    //     $room = $rooms->firstWhere('id', $roomId);
        
    //     \Log::info("HotelDataService: Looking for room ID " . $roomId . ", found: " . ($room ? 'YES' : 'NO'));
        
    //     return $room;
    // }

    /**
     * Получить комнату по ID с связанными данными
     */
    public function getRoomWithRelations($roomId)
    {
        $rooms = $this->getRoomsWithRelations();
        
        // Пробуем найти разными способами (число, строка)
        $room = $rooms->first(function ($room) use ($roomId) {
            return $room['id'] == $roomId || 
                   $room['id'] === (int)$roomId ||
                   $room['id'] === (string)$roomId ||
                   $room['room_number'] == $roomId;
        });
        
        \Log::info("Searching room - Input: {$roomId}, Found: " . ($room ? $room['room_number'] : 'NOT FOUND'));
        
        return $room;
    }

    /**
     * Получить комнату по номеру комнаты
     */
    public function getRoomByNumber($roomNumber)
    {
        return $this->getRoomsWithRelations()->firstWhere('room_number', $roomNumber);
    }

    /**
     * Создать тестовые данные
     */
    public function createDefaultData()
    {
        $defaultData = [
            'categories' => [
                ['id' => 1, 'name' => 'Стандартный', 'description' => 'Комфортные номера'],
                ['id' => 2, 'name' => 'Студия', 'description' => 'Просторные номера'],
                ['id' => 3, 'name' => 'Люкс', 'description' => 'Роскошные номера']
            ],
            'amenities' => [
                ['id' => 1, 'name' => 'Wi-Fi'],
                ['id' => 2, 'name' => 'Телевизор'],
                ['id' => 3, 'name' => 'Кондиционер'],
                ['id' => 4, 'name' => 'Сейф'],
                ['id' => 5, 'name' => 'Холодильник']
            ],
            'rooms' => [
                [
                    'id' => 101,
                    'room_number' => '101',
                    'category_id' => 1,
                    'price' => 3200,
                    'capacity' => 2,
                    'amenity_ids' => [1, 2, 3],
                    'image' => 'room-101.jpg',
                    'is_available' => true,
                    'description' => 'Уютный стандартный номер с современным дизайном'
                ],
                [
                    'id' => 102,
                    'room_number' => '102',
                    'category_id' => 1,
                    'price' => 3000,
                    'capacity' => 1,
                    'amenity_ids' => [1, 2],
                    'image' => 'room-102.jpg',
                    'is_available' => true,
                    'description' => 'Бюджетный стандартный номер'
                ],
                [
                    'id' => 103,
                    'room_number' => '103',
                    'category_id' => 1,
                    'price' => 3500,
                    'capacity' => 2,
                    'amenity_ids' => [1, 2, 3, 4],
                    'image' => 'room-103.jpg',
                    'is_available' => false,
                    'description' => 'Просторный стандартный номер с балконом'
                ],
                [
                    'id' => 201,
                    'room_number' => '201',
                    'category_id' => 2,
                    'price' => 5200,
                    'capacity' => 3,
                    'amenity_ids' => [1, 2, 3, 4, 5],
                    'image' => 'room-201.jpg',
                    'is_available' => true,
                    'description' => 'Светлая студия с функциональной мини-кухней'
                ],
                [
                    'id' => 202,
                    'room_number' => '202',
                    'category_id' => 2,
                    'price' => 5800,
                    'capacity' => 4,
                    'amenity_ids' => [1, 2, 3, 4, 5],
                    'image' => 'room-202.jpg',
                    'is_available' => true,
                    'description' => 'Просторная студия для семьи'
                ],
                [
                    'id' => 203,
                    'room_number' => '203',
                    'category_id' => 2,
                    'price' => 6200,
                    'capacity' => 3,
                    'amenity_ids' => [1, 2, 3, 4, 5],
                    'image' => 'room-203.jpg',
                    'is_available' => true,
                    'description' => 'Студия с видом на море'
                ],
                [
                    'id' => 301,
                    'room_number' => '301',
                    'category_id' => 3,
                    'price' => 12000,
                    'capacity' => 4,
                    'amenity_ids' => [1, 2, 3, 4, 5],
                    'image' => 'room-301.jpg',
                    'is_available' => true,
                    'description' => 'Роскошный люкс с джакузи'
                ],
                [
                    'id' => 302,
                    'room_number' => '302',
                    'category_id' => 3,
                    'price' => 9800,
                    'capacity' => 3,
                    'amenity_ids' => [1, 2, 3, 4, 5],
                    'image' => 'room-302.jpg',
                    'is_available' => true,
                    'description' => 'Элегантный люкс-номер с балконом'
                ],
                [
                    'id' => 303,
                    'room_number' => '303',
                    'category_id' => 3,
                    'price' => 15000,
                    'capacity' => 5,
                    'amenity_ids' => [1, 2, 3, 4, 5],
                    'image' => 'room-303.jpg',
                    'is_available' => false,
                    'description' => 'Президентский люкс с двумя комнатами'
                ]
            ]
        ];

        Storage::put($this->dataFile, json_encode($defaultData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        return $defaultData;
    }
}