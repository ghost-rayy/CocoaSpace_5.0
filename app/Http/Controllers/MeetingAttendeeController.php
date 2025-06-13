<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MeetingAttendee;
use App\Models\MeetingRoom;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use App\Mail\AttendeeRegistered;

class MeetingAttendeeController extends Controller
{
    public function indexReg( Request $request)
    {
        $search = $request->input('search');

        $bookings = Booking::where('status', 'Approved')->with('meetingRoom')
        ->when($search, function ($query, $search) {
            return $query->where('e_ticket', 'like', "%{$search}%")
                        ->orWhere('requester', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->get();
        return view('admin.attendee.index', compact('bookings'));
    }

    public function showRegistrationForm($id)
    {
        $bookings = Booking::with('meetingRoom')->findOrFail($id);
        return view('admin.attendee.register', compact('bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|email',
            'department' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'registration_time' => 'required|date',
        ]);

        $attendee = MeetingAttendee::create([
            'booking_id' => $validated['booking_id'], 
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'department' => $validated['department'],
            'phone' => $validated['phone'],
            'created_at' => $validated['registration_time'],
            'updated_at' => $validated['registration_time'],
        ]);

        if (!$attendee) {
            return back()->with('error', 'Failed to register attendee.');
        }

        Mail::to($validated['email'])->send(new RegistrationConfirmation($validated['name']));  

        $bookings = Booking::with('meetingRoom')->get();

        // return view('admin.attendee.index', compact('bookings'))->with('success', 'Attendee Registered Successfully');
        return redirect()->route('admin.attendees.register', $validated['booking_id'])->with('success', 'Attendee registered successfully!');
    }

    public function viewAttendees(Request $request, $id)
{
    $booking = Booking::with('meetingRoom')->findOrFail($id);

    // Start with the attendees relationship and add search conditions
    $attendees = $booking->attendees()
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('admin.attendee.view', compact('booking', 'attendees'));
}

}
