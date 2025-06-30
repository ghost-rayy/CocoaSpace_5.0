<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    use HasFactory;

    protected $table = 'booking_histories';

    protected $fillable = [
        'meeting_room_id',
        'user_id',
        'requester',
        'date',
        'time',
        'duration',
        'extension',
        'reason',
        'capacity',
        'status',
        'e_ticket',
        'meeting_ended',
        'decline_reason',
    ];

    public function meetingRoom()
    {
        return $this->belongsTo(MeetingRoom::class, 'meeting_room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
