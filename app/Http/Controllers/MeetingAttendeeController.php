<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MeetingAttendee;
use App\Models\MeetingRoom;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use App\Mail\AttendeeRegistered;
use Illuminate\Support\Str;

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
        // $validated = $request->validate([
        //     'booking_id' => 'required|exists:bookings,id',
        //     'name' => 'required|string|max:255',
        //     'gender' => 'required|in:Male,Female,Other',
        //     'email' => 'required|email',
        //     'department' => 'required|string|max:255',
        //     'phone' => 'required|string|max:20',
        //     'registration_time' => 'required|date',
        // ]);

        $meeting_code = strtoupper(Str::random(6)); // e.g. 'A1B2C3'

        $attendee = MeetingAttendee::create([
            'booking_id' => $request->booking_id, 
            'name' => $request->name,
            'gender' => $request->gender,
            'email' => $request->email,
            'department' => $request->department,
            'phone' => $request->phone,
            'created_at' => $request->registration_time,
            'updated_at' => $request->registration_time,
            'meeting_code' => $meeting_code,
            'status' => 'not_present',
        ]);

        if (!$attendee) {
            return back()->with('error', 'Failed to register attendee.');
        }

        try {
            Mail::to($attendee->email)->queue(new RegistrationConfirmation($attendee->name, $attendee->meeting_code));
        } catch (\Exception $e) {
            \Log::error('Failed to queue registration confirmation email: ' . $e->getMessage());
            return back()->with('error', 'Failed to queue email: ' . $e->getMessage());
        }

        $bookings = Booking::with('meetingRoom')->get();

        // return view('admin.attendee.index', compact('bookings'))->with('success', 'Attendee Registered Successfully');
        return redirect()->route('admin.attendees.register', $request->booking_id)->with('success', 'Attendee registered successfully!');
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

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $code = strtoupper(trim($request->code));
        $attendee = \App\Models\MeetingAttendee::where('meeting_code', $code)->first();

        if ($attendee) {
            $attendee->status = 'present';
            $attendee->save();
            return back()->with('success', 'Verification successful! You are marked as present.');
        } else {
            return back()->with('error', 'Invalid code or email.');
        }
    }
}
