<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Очищаем таблицу перед заполнением
        Booking::truncate();

        $rooms = Room::all();

        if ($rooms->isEmpty()) {
            $this->command->error('❌ Нет номеров для создания бронирований! Сначала запустите RoomSeeder.');
            return;
        }

        $bookings = [
            [
                'room_id' => $rooms[0]->id,
                'client_name' => 'Иван Петров',
                'client_email' => 'ivan@example.com',
                'client_phone' => '+7 (912) 345-67-89',
                'check_in' => Carbon::now()->addDays(5),
                'check_out' => Carbon::now()->addDays(7),
                'status' => 'confirmed'
            ],
            [
                'room_id' => $rooms[1]->id,
                'client_name' => 'Мария Сидорова',
                'client_email' => 'maria@example.com', 
                'client_phone' => '+7 (923) 456-78-90',
                'check_in' => Carbon::now()->addDays(10),
                'check_out' => Carbon::now()->addDays(12),
                'status' => 'pending'
            ],
            [
                'room_id' => $rooms[2]->id,
                'client_name' => 'Алексей Козлов',
                'client_email' => 'alex@example.com',
                'client_phone' => '+7 (934) 567-89-01',
                'check_in' => Carbon::now()->addDays(3),
                'check_out' => Carbon::now()->addDays(6),
                'status' => 'cancelled'
            ]
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
        }

        $this->command->info('✅ Тестовые бронирования успешно созданы! Создано: ' . count($bookings) . ' бронирований');
    }
}