<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MeetingRoom;
use App\Models\Booking;
use App\Models\BookingHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\BookingRequested;
use App\Mail\AttendeeRegistered;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use App\Models\BookingDocument;


class AdminController extends Controller
{
    public function home(Request $request) {
        $period = $request->input('period', 'month'); // default to month

        $pendingBookings = Booking::where('status', 'Pending')->count();
        $totalUsers = User::where('is_admin', false)->count(); // exclude admins
        $totalBookings = Booking::count();

        $rooms = MeetingRoom::with(['bookings' => function($query) {
            $query->where('status', 'Approved');
        }])->get();

        $bookedRooms = $rooms->count();
        $availableRooms = MeetingRoom::count() - $bookedRooms;

        // Prepare booking data based on period
        $labels = [];
        $data = [];

        switch ($period) {
            case 'day':
                // Bookings per distinct time for today (group by time string)
                $labels = [];
                $data = [];
                $bookingsToday = Booking::whereDate('date', now()->toDateString())
                    ->select('time', \DB::raw('count(*) as count'))
                    ->groupBy('time')
                    ->orderBy('time')
                    ->get();
                $historyToday = BookingHistory::whereDate('date', now()->toDateString())
                    ->select('time', \DB::raw('count(*) as count'))
                    ->groupBy('time')
                    ->orderBy('time')
                    ->get();
                $timeCounts = [];
                foreach ($bookingsToday as $booking) {
                    $timeCounts[$booking->time] = ($timeCounts[$booking->time] ?? 0) + $booking->count;
                }
                foreach ($historyToday as $history) {
                    $timeCounts[$history->time] = ($timeCounts[$history->time] ?? 0) + $history->count;
                }
                ksort($timeCounts);
                foreach ($timeCounts as $time => $count) {
                    $labels[] = $time;
                    $data[] = $count;
                }
                break;

            case 'week':
                // Bookings per day for current week (Monday to Sunday)
                $startOfWeek = now()->startOfWeek();
                $labels = [];
                $data = [];
                for ($i = 0; $i < 7; $i++) {
                    $date = $startOfWeek->copy()->addDays($i);
                    $labels[] = $date->format('D');
                    $count = Booking::whereDate('date', $date->toDateString())->count();
                    $historyCount = BookingHistory::whereDate('date', $date->toDateString())->count();
                    $data[] = $count + $historyCount;
                }
                break;

            case 'year':
                // Bookings per month for current year
                $labels = [];
                $data = [];
                for ($month = 1; $month <= 12; $month++) {
                    $labels[] = date('F', mktime(0, 0, 0, $month, 1));
                    $count = Booking::whereYear('date', now()->year)
                        ->whereMonth('date', $month)
                        ->count();
                    $historyCount = BookingHistory::whereYear('date', now()->year)
                        ->whereMonth('date', $month)
                        ->count();
                    $data[] = $count + $historyCount;
                }
                break;

            case 'month':
            default:
                // Bookings per day for current month
                $daysInMonth = now()->daysInMonth;
                $labels = [];
                $data = [];
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $labels[] = $day;
                    $count = Booking::whereYear('date', now()->year)
                        ->whereMonth('date', now()->month)
                        ->whereDay('date', $day)
                        ->count();
                    $historyCount = BookingHistory::whereYear('date', now()->year)
                        ->whereMonth('date', now()->month)
                        ->whereDay('date', $day)
                        ->count();
                    $data[] = $count + $historyCount;
                }
                break;
        }

        return view('admin.home', compact(
            'pendingBookings',
            'totalUsers',
            'totalBookings',
            'availableRooms',
            'bookedRooms',
            'labels',
            'data',
            'rooms',
            'period'
        ));
    }

    public function adash()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $totalUsers = User::count();

        return view('admin.dashboard', compact('users','totalUsers'));
    }

    public function create()
    {
        return view('admin.user-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'staff_id' => 'required|string|unique:users,staff_id',
            'department' => 'required|string|max:255',
            'office_number' => 'required|string|max:50',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        $user = new \App\Models\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->staff_id = $request->staff_id;
        $user->department = $request->department;
        $user->office_number = $request->office_number;
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User added successfully!');
    }

    public function edit(User $user)
    {
        return view('admin.user-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        
        $user->update([
            'role' => $request->role,
            'name' => $request->name,
            'email' => $request->email,
            'staff_id' => $request->staff_id,
            'department' => $request->department,
            'office_number' => $request->office_number,
        ]);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);

        if ($request->status === 'Approved') {
            $booking->e_ticket = $booking->generateETicket();
        } else if ($request->status === 'Declined') {
            // Move to booking_histories
            \App\Models\BookingHistory::create([
                'meeting_room_id' => $booking->meeting_room_id,
                'user_id' => $booking->user_id,
                'requester' => $booking->requester,
                'date' => $booking->date,
                'time' => $booking->time,
                'duration' => $booking->duration,
                'extension' => $booking->extension,
                'reason' => $booking->reason,
                'capacity' => $booking->capacity,
                'status' => 'Declined',
                'e_ticket' => $booking->e_ticket,
                'meeting_ended' => $booking->meeting_ended,
                'decline_reason' => $request->decline_reason,
            ]);
            $booking->delete();
            return redirect()->route('admin.bookings')->with('success', 'Booking declined and moved to history.');
        }

        $booking->save();

        return redirect()->route('admin.bookings')->with('success', 'Booking status updated successfully.');
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }


    public function bookings(Request $request)
    {
        $search = $request->input('search');

        $bookings = Booking::with('meetingRoom')
            ->when($search, function ($query, $search) {
                return $query->where('e_ticket', 'like', "%{$search}%")
                            ->orWhere('requester', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.bookings', compact('bookings', 'search'));
    }


    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'Not Started']);
        return redirect()->route('admin.bookings')->with('success', 'Booking Approved');
    }

    public function decline($id)
    {
        $booking = Booking::findOrFail($id);
        
        BookingHistory::create([
            'meeting_room_id' => $booking->meeting_room_id,
            'user_id' => $booking->user_id,
            'requester' => $booking->requester,
            'date' => $booking->date,
            'time' => $booking->time,
            'duration' => $booking->duration,
            'extension' => $booking->extension,
            'reason' => $booking->reason,
            'capacity' => $booking->capacity,
            'status' => 'Declined',
            'e_ticket' => $booking->e_ticket,
            'meeting_ended' => true
        ]);

        $booking->delete();

        return redirect()->route('admin.bookings')->with('success', 'Booking declined and moved to history.');
    }

    public function index()
    {
        // $rooms = MeetingRoom::all();

        $rooms = MeetingRoom::with('bookings')->get();
        return view('admin.meeting_rooms', compact('rooms'));
    }

    public function createRoom()
    {
        return view('admin.create');
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'room_number' => 'required|string|unique:meeting_rooms',
            'floor' => 'required|string',
            'capacity' => 'required|string',
        ]);

        MeetingRoom::create([
            'name' => $request->name,
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('admin.room')->with('success', 'Meeting Room Added Successfully');
    }

    public function editRoom(MeetingRoom $rooms)
    {
        return view('admin.edit', compact('rooms'));
    }

    public function updateRoom(Request $request, MeetingRoom $rooms)
    {
        $request->validate([
            'name' => 'required|string',
            'room_number' => 'required|string|unique:meeting_rooms,room_number,' . $rooms->id,
            'floor' => 'required|string',
            'capacity' => 'required|string',
        ]);

        $rooms->update($request->all());

        return redirect()->route('admin.room')->with('success', 'Meeting Room Updated Successfully');
    }

    public function destroyRoom(MeetingRoom $rooms)
    {
        $rooms->delete();
        return redirect()->route('admin.room')->with('success', 'Meeting Room Deleted Successfully');
    }

    public function request()
    {
        $pendingBookings = Booking::where('status', 'Pending')->orderBy('created_at', 'desc')->get();
        return view('admin.requests', compact('pendingBookings'));
    }

    public function refreshPendingTable()
    {
        $pendingBookings = Booking::where('status', 'Pending')->get();
        return view('admin.partials.pending-table', compact('pendingBookings'));
    }

    public function add()
    {
        $rooms = MeetingRoom::all();
        return view('admin.add', compact('rooms'));
    }

    public function addStore(Request $request) {
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

        // Determine status based on user role
        $status = 'Pending';
        if (auth()->user()->role === 'admin' || auth()->user()->is_admin) {
            $status = 'Approved';
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
            'status' => $status,
        ]);

        // If auto-approved, assign e-ticket
        if ($status === 'Approved') {
            $booking->e_ticket = $booking->generateETicket();
            $booking->save();
        }

        event(new BookingRequested($booking));

        return redirect()->back()->with('success', 'Meeting room booked successfully!');
    }

    public function checkAvailability(Request $request)
    {
        $conflict = Booking::where('meeting_room_id', $request->meeting_room_id)
        ->where('date', $request->date)
        ->where('time', $request->time)
        ->where('meeting_ended', 0)
        ->exists();

    return response()->json(['conflict' => $conflict]);
    }

    public function endMeeting($id)
    {
        $booking = Booking::findOrFail($id);

        // Convert time to 24-hour format for database compatibility
        $time24 = date("H:i:s", strtotime($booking->time));

        // Copy booking data to booking_histories
        BookingHistory::create([
            'meeting_room_id' => $booking->meeting_room_id,
            'user_id' => $booking->user_id,
            'requester' => $booking->requester,
            'date' => $booking->date,
            'time' => $time24,
            'duration' => $booking->duration,
            'extension' => $booking->extension,
            'reason' => $booking->reason,
            'capacity' => $booking->capacity,
            'status' => $booking->status,
            'e_ticket' => $booking->e_ticket,
            'meeting_ended' => true,
        ]);

        // Delete the booking from bookings table
        $booking->delete();

        return redirect()->back()->with('success', 'Meeting has ended and moved to history.');
    }

    public function bookingHistory(Request $request)
    {
        $search = $request->input('search');

        $bookingHistories = BookingHistory::with('meetingRoom')
            ->when($search, function ($query, $search) {
                return $query->where('e_ticket', 'like', "%{$search}%")
                            ->orWhere('requester', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.booking_history', compact('bookingHistories', 'search'));
    }

    /**
     * Show the attach document modal (for sidebar modal, just needs bookings list)
     */
    public function showAttachDocument()
    {
        $bookings = Booking::orderBy('created_at', 'desc')->get();
        return view('admin.attach_document', compact('bookings'));
    }

    public function uploadBookingDocument(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,psd,xls,xlsx,ppt,pptx|max:55120', 
        ]);

        $file = $request->file('document');
        $path = $file->store('booking_documents', 'public');

        BookingDocument::create([
            'booking_id' => $request->booking_id,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);

        return back()->with('success', 'Document attached successfully!');
    }
}
