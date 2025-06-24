<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'room_number', 'floor', 'capacity'];

    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'meeting_room_id');
    }
}
