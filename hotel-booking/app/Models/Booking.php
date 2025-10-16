<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'client_name',
        'client_email', 
        'client_phone',
        'check_in',
        'check_out',
        'status'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date'
    ];

    // Убираем отношение belongsTo, т.к. rooms нет в БД
    // public function room()
    // {
    //     return $this->belongsTo(Room::class);
    // }

    /**
     * Проверяет доступность номера на указанные даты
     */
    public static function isRoomAvailable($roomId, $checkIn, $checkOut, $excludeBookingId = null)
    {
        $query = self::where('room_id', $roomId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut])
                  ->orWhere(function($q2) use ($checkIn, $checkOut) {
                      $q2->where('check_in', '<=', $checkIn)
                         ->where('check_out', '>=', $checkOut);
                  });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->count() === 0;
    }

    /**
     * Получает занятые даты для номера
     */
    public static function getBusyDates($roomId)
    {
        return self::where('room_id', $roomId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->select('check_in', 'check_out')
            ->get()
            ->map(function($booking) {
                return [
                    'from' => $booking->check_in->format('Y-m-d'),
                    'to' => $booking->check_out->format('Y-m-d')
                ];
            });
    }
}