<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id'); // ← Убираем foreignId(), используем unsignedBigInteger
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone');
            $table->date('check_in');
            $table->date('check_out');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
            
            // Индексы для оптимизации (без foreign key)
            $table->index('room_id');
            $table->index('status');
            $table->index(['check_in', 'check_out']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};