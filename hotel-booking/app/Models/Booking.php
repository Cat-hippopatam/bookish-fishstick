<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'client_name',     // меняем first_name + last_name на client_name
        'client_email',    // меняем email на client_email
        'client_phone',    // меняем phone на client_phone
        'check_in',        // меняем check_in_date на check_in
        'check_out',       // меняем check_out_date на check_out
        'status'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}