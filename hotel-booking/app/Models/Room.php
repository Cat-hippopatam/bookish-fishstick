<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'category_id',
        'category_name',
        'price',
        'capacity',
        'amenities',
        'amenity_ids',
        'image',
        'is_available',
        'description'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'amenity_ids' => 'array'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Получить удобства в виде массива
     */
    public function getAmenitiesArrayAttribute()
    {
        return explode(', ', $this->amenities);
    }
}