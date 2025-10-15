<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Очищаем таблицу перед заполнением
        Room::truncate();

        $rooms = [
            [
                'room_number' => '101',
                'category' => 'Стандартный',
                'price' => 2500,
                'capacity' => 2,
                'amenities' => 'Wi-Fi, Телевизор, Кондиционер, Сейф',
                'image' => 'standart.png',
                'is_available' => true,
                'description' => 'Уютный стандартный номер с всеми необходимыми удобствами'
            ],
            [
                'room_number' => '201',
                'category' => 'Студия', 
                'price' => 3500,
                'capacity' => 3,
                'amenities' => 'Wi-Fi, Телевизор, Кондиционер, Мини-кухня, Сейф',
                'image' => 'studio.jpg',
                'is_available' => true,
                'description' => 'Просторная студия с мини-кухней для комфортного проживания'
            ],
            [
                'room_number' => '301',
                'category' => 'Люкс',
                'price' => 5000,
                'capacity' => 4,
                'amenities' => 'Wi-Fi, Телевизор, Кондиционер, Мини-бар, Джакузи, Сейф, Вид на море',
                'image' => 'lux.png',
                'is_available' => true,
                'description' => 'Роскошный люкс-номер с панорамным видом и всеми удобствами'
            ],
            [
                'room_number' => '102',
                'category' => 'Эконом',
                'price' => 1800,
                'capacity' => 1,
                'amenities' => 'Wi-Fi, Телевизор',
                'image' => 'standart.png',
                'is_available' => false,
                'description' => 'Бюджетный вариант для экономных путешественников'
            ]
        ];

        foreach ($rooms as $roomData) {
            Room::create($roomData);
        }

        $this->command->info('✅ Тестовые номера успешно созданы! Создано: ' . count($rooms) . ' номеров');
    }
}