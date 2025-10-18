<?php

namespace Database\Seeders;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        Booking::truncate();

        $bookings = [
            [
                'room_id' => 101,
                'client_name' => 'Иван Петров',
                'client_email' => 'ivan@example.com',
                'client_phone' => '+7 (912) 345-67-89',
                'check_in' => Carbon::now()->addDays(2),
                'check_out' => Carbon::now()->addDays(5),
                'status' => 'confirmed'
            ],
            [
                'room_id' => 201,
                'client_name' => 'Мария Сидорова', 
                'client_email' => 'maria@example.com',
                'client_phone' => '+7 (923) 456-78-90',
                'check_in' => Carbon::now()->addDays(7),
                'check_out' => Carbon::now()->addDays(10),
                'status' => 'pending'
            ],
            [
                'room_id' => 101, // Этот номер уже занят на даты
                'client_name' => 'Алексей Козлов',
                'client_email' => 'alex@example.com', 
                'client_phone' => '+7 (934) 567-89-01',
                'check_in' => Carbon::now()->addDays(1),
                'check_out' => Carbon::now()->addDays(3),
                'status' => 'cancelled' // Отмененное бронирование не мешает
            ]
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
        }

        $this->command->info('✅ Тестовые бронирования созданы!');
        $this->command->info('📅 Номер 101 занят с ' . Carbon::now()->addDays(2)->format('d.m.Y') . ' по ' . Carbon::now()->addDays(5)->format('d.m.Y'));
    }
}