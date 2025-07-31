<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetingRoom; // or your actual model
use App\Models\Booking;     // if details are in Booking

class MeetingRoomController extends Controller
{
    public function show($code)
    {
        // Example: Find booking by code, then get room and details
        $booking = Booking::where('code', $code)->first();
        if (!$booking) {
            abort(404, 'Meeting not found');
        }

        $meetingRoomName = $booking->meetingRoom->name ?? 'Unknown Room';
        $meetingTime = $booking->start_time->format('g:iA') . ' - ' . $booking->end_time->format('g:iA');
        $meetingTitle = $booking->title ?? 'Meeting';
        $meetingStatus = $booking->status ?? 'In Progress';

        return view('auth.meeting-room', compact('meetingRoomName', 'meetingTime', 'meetingTitle', 'meetingStatus'));
    }
}