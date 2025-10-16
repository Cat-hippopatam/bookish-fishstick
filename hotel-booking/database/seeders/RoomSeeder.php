<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::truncate();

        $hotelData = $this->loadHotelData();

        if (empty($hotelData['rooms'])) {
            // Создаем тестовые данные если JSON нет
            $this->createTestRooms();
            return;
        }

        foreach ($hotelData['rooms'] as $roomData) {
            $categoryName = $this->getCategoryName($hotelData['categories'], $roomData['category_id']);
            
            Room::create([
                'id' => $roomData['id'],
                'room_number' => $roomData['room_number'],
                'category_id' => $roomData['category_id'],
                'category_name' => $categoryName,
                'price' => $roomData['price'],
                'capacity' => $roomData['capacity'],
                'amenities' => $this->getAmenitiesString($hotelData['amenities'], $roomData['amenity_ids']),
                'amenity_ids' => $roomData['amenity_ids'],
                'image' => $roomData['image'],
                'is_available' => $roomData['is_available'],
                'description' => $roomData['description']
            ]);
        }

        $this->command->info('✅ Номера успешно созданы из JSON данных! Создано: ' . count($hotelData['rooms']) . ' номеров');
    }

    protected function loadHotelData()
    {
        $jsonFile = 'hotel_data.json';
        
        if (!Storage::exists($jsonFile)) {
            return ['rooms' => [], 'categories' => [], 'amenities' => []];
        }

        $content = Storage::get($jsonFile);
        return json_decode($content, true) ?? ['rooms' => [], 'categories' => [], 'amenities' => []];
    }

    protected function getCategoryName($categories, $categoryId)
    {
        foreach ($categories as $category) {
            if ($category['id'] == $categoryId) {
                return $category['name'];
            }
        }
        return 'Неизвестная категория';
    }

    protected function getAmenitiesString($amenities, $amenityIds)
    {
        $amenityNames = [];
        
        foreach ($amenities as $amenity) {
            if (in_array($amenity['id'], $amenityIds)) {
                $amenityNames[] = $amenity['name'];
            }
        }
        
        return implode(', ', $amenityNames);
    }

    protected function createTestRooms()
    {
        $rooms = [
            [
                'id' => 101,
                'room_number' => '101',
                'category_id' => 1,
                'category_name' => 'Стандартный',
                'price' => 3200,
                'capacity' => 2,
                'amenities' => 'Wi-Fi, Телевизор, Кондиционер',
                'amenity_ids' => [1, 2, 3],
                'image' => 'room-101.jpg',
                'is_available' => true,
                'description' => 'Уютный стандартный номер'
            ],
            [
                'id' => 102,
                'room_number' => '102',
                'category_id' => 1,
                'category_name' => 'Стандартный',
                'price' => 3000,
                'capacity' => 1,
                'amenities' => 'Wi-Fi, Телевизор',
                'amenity_ids' => [1, 2],
                'image' => 'room-102.jpg',
                'is_available' => true,
                'description' => 'Бюджетный стандартный номер'
            ]
        ];

        foreach ($rooms as $roomData) {
            Room::create($roomData);
        }

        $this->command->info('✅ Тестовые номера созданы!');
    }
}