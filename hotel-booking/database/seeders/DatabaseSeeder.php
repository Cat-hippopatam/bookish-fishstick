<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // RoomSeeder больше не нужен!
            BookingSeeder::class,
        ]);

        $this->command->info('🎉 Данные успешно созданы!');
    }
}