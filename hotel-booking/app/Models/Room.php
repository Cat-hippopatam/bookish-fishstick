<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',     // добавляем номер помещения
        'category',
        'price',           // меняем price_per_person на price
        'capacity',        // добавляем вместимость
        'amenities',       // меняем characteristics на amenities
        'image',           // меняем image_path на image
        'is_available',
        'description'      // добавляем описание
    ];

    protected $casts = [
        'is_available' => 'boolean'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}