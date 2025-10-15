<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Отключаем проверку внешних ключей для очистки таблиц
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        $this->call([
            RoomSeeder::class,
            BookingSeeder::class,
        ]);

        // Включаем проверку внешних ключей обратно
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('🎉 Все тестовые данные успешно созданы!');
    }
}