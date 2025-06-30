<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MeetingAttendee;
use App\Models\MeetingRoom;
use App\Mail\AttendeeRegistered;
use Illuminate\Support\Facades\Mail;
use App\Services\AfricasTalkingService;
use App\Mail\RegistrationConfirmation;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $bookings = Booking::where('status', 'Approved')
            ->when($search, function ($query, $search) {
                return $query->where('requester', 'like', "%{$search}%")
                             ->orWhere('e_ticket', 'like', "%{$search}%");
            })
            ->orderBy('date', 'desc')
            ->get();

        return view('register.attendees.index', compact('bookings'));
    }

    public function showRegistrationForm($id)
    {
        $bookings = Booking::with('meetingRoom')->findOrFail($id);
        return view('register.attendees.register', compact('bookings'), ['id' => $id]);
    }

    public function showRegistrationReplicaForm($id)
    {
        // Check if the meeting has ended (exists in booking_histories)
        $history = \App\Models\BookingHistory::where('id', $id)->first();
        if ($history) {
            return redirect()->route('enter.code.submit')->with('error', 'This meeting has ended. You cannot file forms for ended meetings.');
        }
        $bookings = Booking::with('meetingRoom')->findOrFail($id);
        return view('admin.attendee.register-replica', compact('bookings'), ['id' => $id]);
    }

    public function storeReplica(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|email',
            'department' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $attendee = MeetingAttendee::create([
            'booking_id' => $validated['booking_id'], 
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'department' => $validated['department'],
            'phone' => $validated['phone'],
            // 'meeting_code' => strtoupper(Str::random(6)),
            'status' => 'present',
        ]);

        if (!$attendee) {
            return back()->with('error', 'Failed to register attendee.');
        }

        // Mail::to($attendee->email)->send(new RegistrationConfirmation($attendee->name, null));
        // Mail::to($validated['email'])->queue(new RegistrationConfirmation($validated['name']));

        try {
            Mail::to($attendee->email)->send(new RegistrationConfirmation($attendee->name, null));
        } catch (\Exception $e) {
            \Log::error('Failed to queue registration confirmation email: ' . $e->getMessage());
            return back()->with('error', 'Failed to queue email: ' . $e->getMessage());
        }

        $bookings = Booking::with('meetingRoom')->get();

        return redirect()->route('admin.attendees.register-replica', $validated['booking_id'])->with('success', 'Attendee registered successfully!');
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
        ]);

        $attendee = MeetingAttendee::create([
            'booking_id' => $validated['booking_id'], 
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'department' => $validated['department'],
            'phone' => $validated['phone'],
            // 'meeting_code' => strtoupper(Str::random(6)),
            'status' => 'present',
        ]);

        if (!$attendee) {
            return back()->with('error', 'Failed to register attendee.');
        }

        // Mail::to($attendee->email)->send(new RegistrationConfirmation($attendee->name, null));
        // Mail::to($validated['email'])->queue(new RegistrationConfirmation($validated['name']));

        try {
            Mail::to($attendee->email)->send(new RegistrationConfirmation($attendee->name, null));
        } catch (\Exception $e) {
            \Log::error('Failed to queue registration confirmation email: ' . $e->getMessage());
            return back()->with('error', 'Failed to queue email: ' . $e->getMessage());
        }

        $bookings = Booking::with('meetingRoom')->get();

        return redirect()->route('register.attendees.register', $validated['booking_id'])->with('success', 'Attendee registered successfully!');
    }

    public function uploadFlyer(Request $request)
    {
        $request->validate([
            'flyer' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('flyer')) {
            $image = $request->file('flyer');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('flyers'), $imageName);

            // Store flyer path in session for demo purposes
            session(['flyer_path' => 'flyers/' . $imageName]);

           return redirect()->route('register.attendees.index')->with('success', 'Flyer uploaded successfully.');
        }

        return back()->with('error', 'Please select a valid image file.');
    }

    public function uploadFlyerForBooking(Request $request, $bookingId)
    {
        $request->validate([
            'flyer' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $booking = Booking::findOrFail($bookingId);

        if ($request->hasFile('flyer')) {
            // Delete old flyer file if exists
            if ($booking->flyer_path && file_exists(public_path($booking->flyer_path))) {
                unlink(public_path($booking->flyer_path));
            }

            $image = $request->file('flyer');
            $imageName = time() . '_' . $bookingId . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('flyers'), $imageName);

            $booking->flyer_path = 'flyers/' . $imageName;
            $booking->save();

            return redirect()->route('register.attendees.index')->with('success', 'Flyer uploaded successfully for booking ID ' . $bookingId);
        }

        return back()->with('error', 'Please select a valid image file.');
    }
}
