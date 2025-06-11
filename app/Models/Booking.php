<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['requester', 'duration', 'date', 'time', 'extension', 'reason', 'status', 'user_id','meeting_room_id', 'capacity', 'e_ticket', 'flyer_path'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generateETicket()
    {
        $prefix = 'CS' . strtoupper(substr($this->user->name, 0, 2));
        $unique = base_convert($this->id . time(), 10, 36);
        return $prefix . strtoupper(substr($unique, -3));
    }

    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class);
    }

    public function attendees()
    {
        return $this->hasMany(MeetingAttendee::class);
    }


}
