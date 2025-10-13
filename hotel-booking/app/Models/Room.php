<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'price_per_person', 
        'characteristics',
        'image_path',
        'is_available'
    ];

    protected $casts = [
        'characteristics' => 'array',
        'is_available' => 'boolean'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
