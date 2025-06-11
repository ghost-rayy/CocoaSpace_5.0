<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\MeetingRoom;
use App\Events\BookingRequested;


class UserController extends Controller
{
    public function sdash()
    {
        return view('user.dashboard');
    }

    public function createBooking(){
        $rooms = MeetingRoom::all();
        return view('user.create-booking', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'meeting_room_id' => 'required|exists:meeting_rooms,id',
            'requester' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'required|integer|min:1',
            'extension' => 'required|string',
            'reason' => 'required|string',
            'capacity' => 'required|integer',
        ]);

        $conflict = Booking::where('meeting_room_id', $request->meeting_room_id)
        ->where('date', $request->date)
        ->where('time', $request->time)
        ->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'This meeting room is already booked at the selected time.');
        }

        $booking = Booking::create([

            'meeting_room_id' => $request->meeting_room_id,

            'user_id' => Auth::id(),

            'requester' => $request->requester,

            'date' => $request->date,

            'time' => $request->time,

            'duration' => $request->duration,

            'extension' => $request->extension,

            'reason' => $request->reason,

            'capacity' => $request->capacity,

        ]);

        event(new BookingRequested($booking));

        return redirect()->route('user.booking')->with('success', 'Meeting room booked successfully!');
    }

    public function checkAvailability(Request $request)
    {
        $conflict = Booking::where('meeting_room_id', $request->meeting_room_id)
        ->where('date', $request->date)
        ->where('time', $request->time)
        ->exists();

    return response()->json(['conflict' => $conflict]);
    }

    public function booking()
    {
        $bookings = Booking::where('user_id', Auth::id())->latest()->get();
        $meetingRooms = MeetingRoom::all();

        return view('user.booking', compact('bookings', 'meetingRooms'));
    }


    public function refreshBookings()
    {
        $bookings = auth()->user()->bookings()->latest()->get(); // assuming relationship exists
        return view('user.partials.bookings-table', compact('bookings'));
    }

}
