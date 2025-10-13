<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'first_name',
        'last_name', 
        'phone',
        'email',
        'check_in_date',
        'check_out_date',
        'status'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}