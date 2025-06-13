<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAttendee extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'name', 'gender', 'email', 'department', 'phone', 'created_at', 'updated_at'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function attendees()
    {
        return $this->hasMany(MeetingAttendee::class);
    }

}
