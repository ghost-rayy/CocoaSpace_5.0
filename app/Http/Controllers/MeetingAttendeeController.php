<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\MeetingAttendee;
use App\Models\MeetingRoom;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\RegistrationConfirmation;
use App\Mail\AttendeeRegistered;
use Illuminate\Support\Str;
use App\Models\MeetingAttendee as Attendee;
use Illuminate\Support\Facades\Validator;
use App\Models\BookingDocument;
use App\Mail\VerificationConfirmation;
use Barryvdh\DomPDF\Facade\Pdf;

class MeetingAttendeeController extends Controller
{
    public function indexReg(Request $request)
    {
        $search = $request->input('search');

        $bookings = Booking::whereIn('status', ['Approved', 'Not Started', 'Started'])
            ->with('meetingRoom')
            ->when($search, function ($query, $search) {
                return $query->where('e_ticket', 'like', "%{$search}%")
                            ->orWhere('requester', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.attendee.index', compact('bookings'));
    }

    public function showRegistrationForm(Request $request, $id)
    {
        $bookings = Booking::with('meetingRoom')->findOrFail($id);

        $attendees = $bookings->attendees()
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.attendee.register', compact('bookings', 'attendees'));
    }

    public function store(Request $request)
    {
        $meeting_code = strtoupper(Str::random(6));
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
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Failed to register attendee.']);
            }
            return back()->with('error', 'Failed to register attendee.');
        }
        // Do not send email here!
        if ($request->ajax()) {
            return response()->json(['success' => true, 'attendee' => $attendee]);
        }
        
        $bookings = Booking::with('meetingRoom')->get();
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
            
            $documents = BookingDocument::where('booking_id', $attendee->booking_id)->get();
            $attachments = $documents->map(function($doc) {
                return [
                    'file' => storage_path('app/' . $doc->file_path),
                    'name' => $doc->original_name,
                ];
            })->toArray();
            \Mail::to($attendee->email)->queue(new VerificationConfirmation($attendee->name, $attachments));
            return back()->with('success', 'Verification successful! You are marked as present.');
                } else {
            return back()->with('error', 'Invalid code or email.');
        }
    }

    public function showImportForm()
    {
        $bookings = Booking::with('meetingRoom')
            ->whereIn('status', ['Approved', 'Not Started', 'Started'])
            ->get();
        return view('admin.attendee.import', compact('bookings'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);
        $file = $request->file('csv_file');
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);
        $rows = [];
        $attendees = [];
        while (($data = fgetcsv($handle)) !== FALSE) {
            $rows[] = array_combine($header, $data);
        }
        fclose($handle);
        foreach ($rows as $row) {
            $attendee = MeetingAttendee::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'gender' => $row['gender'],
                'department' => $row['department'],
                'booking_id' => $request->booking_id,
                'meeting_code' => $meeting_code = strtoupper(\Str::random(6)),
                'status' => 'not_present',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if ($attendee) {
                $attendees[] = $attendee;
            }
        }
        // Do not send emails here!
        if ($request->ajax()) {
            return response()->json(['success' => true, 'attendees' => $attendees]);
        }
        return redirect()->back()->with('success', 'Attendees imported successfully!');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendees_template.csv"',
        ];
        $columns = ['name', 'gender', 'email', 'department', 'phone'];
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function handleEnterCode(Request $request)
    {
        $request->validate([
            'e_ticket' => 'required'
        ]);
        $e_ticket = strtoupper(trim($request->e_ticket));
        $booking = \App\Models\Booking::where('e_ticket', $e_ticket)->first();

        if ($booking) {
            $booking->status = 'Started';
            $booking->save();
            return response()->json([
                'success' => true,
                'booking' => $booking,
                'message' => 'Meeting found successfully!'
            ]);
        } else {
            // Check if the code exists in booking history (meeting ended)
            $history = \App\Models\BookingHistory::where('e_ticket', $e_ticket)->first();
            if ($history) {
                return response()->json([
                    'success' => false,
                    'message' => 'This meeting has ended. You cannot file forms for ended meetings.'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Invalid e-ticket.'
            ]);
        }
    }

    public function documents($bookingId)
    {
        $documents = \App\Models\BookingDocument::where('booking_id', $bookingId)->get();
        return response()->json($documents);
    }

    public function showInProgress($id)
    {
        $booking = Booking::with('meetingRoom')->findOrFail($id);
        return view('admin.attendee.in-progress', compact('booking'));
    }

    public function viewHistoryAttendees(Request $request, $id)
    {
        $bookingHistory = \App\Models\BookingHistory::with('meetingRoom')->findOrFail($id);

        $attendees = $this->attendeesForHistory($bookingHistory)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.attendee.view-history', compact('bookingHistory', 'attendees'));
    }

    public function exportExcel($id)
    {
        $bookingHistory = \App\Models\BookingHistory::with('meetingRoom')->findOrFail($id);
        $attendees = $this->attendeesForHistory($bookingHistory)->get();

        $filename = 'attendees_' . $bookingHistory->requester . '_' . $bookingHistory->date . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendees) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Name', 'Email', 'Phone', 'Department', 'Gender', 'Status', 'Registration Date']);
            
            // Add data
            foreach ($attendees as $attendee) {
                fputcsv($file, [
                    $attendee->name,
                    $attendee->email,
                    $attendee->phone,
                    $attendee->department,
                    $attendee->gender,
                    ucfirst($attendee->status),
                    $attendee->created_at->format('M d, Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf($id)
    {
        $bookingHistory = \App\Models\BookingHistory::with('meetingRoom')->findOrFail($id);
        $attendees = $this->attendeesForHistory($bookingHistory)->get();

        $safeTitle = preg_replace('/[^A-Za-z0-9_-]+/', '_', $bookingHistory->requester);
        $filename = 'attendees_' . $safeTitle . '_' . $bookingHistory->date . '.pdf';

        return Pdf::loadView('admin.attendee.export-pdf', compact('attendees', 'bookingHistory'))
            ->setPaper('a4', 'landscape')
            ->download($filename);
    }

    private function attendeesForHistory($bookingHistory)
    {
        return \App\Models\MeetingAttendee::where('booking_id', $bookingHistory->booking_id ?? 0);
    }

    public function deleteDocument($id)
    {
        $doc = \App\Models\BookingDocument::findOrFail($id);
        // Delete the file from storage
        if (\Storage::disk('public')->exists(str_replace('booking_documents/', '', $doc->file_path))) {
            \Storage::disk('public')->delete(str_replace('booking_documents/', '', $doc->file_path));
        } elseif (\Storage::exists($doc->file_path)) {
            \Storage::delete($doc->file_path);
        }
        $doc->delete();
        return response()->json(['success' => true]);
    }

    public function sendCustomEmail(Request $request)
    {
        $request->validate([
            'recipients' => 'required|string',
            'subject' => 'required|string',
            'body' => 'required|string',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:10240',
        ]);
        $recipients = array_map('trim', explode(',', $request->recipients));
        $subject = $request->subject;
        $body = $request->body;
        $attachments = $request->file('attachments', []);
        $failed = [];
        foreach ($recipients as $recipient) {
            // Find attendee by email
            $attendee = \App\Models\MeetingAttendee::where('email', $recipient)->latest()->first();
            $personalizedBody = $body;
            if ($attendee) {
                $personalizedBody = str_replace('[Name]', $attendee->name, $personalizedBody);
                $personalizedBody = str_replace('[Meeting Code]', $attendee->meeting_code, $personalizedBody);
            }
            try {
                \Mail::send([], [], function ($message) use ($recipient, $subject, $personalizedBody, $attachments) {
                    $message->to($recipient)
                        ->subject($subject)
                        ->html($personalizedBody);
                    if ($attachments) {
                        foreach ($attachments as $file) {
                            $message->attach($file->getRealPath(), [
                                'as' => $file->getClientOriginalName(),
                                'mime' => $file->getMimeType(),
                            ]);
                        }
                    }
                });
                Log::info('Custom email sent', [
                    'email' => $recipient,
                    'subject' => $subject,
                    'attendee_id' => $attendee->id ?? null,
                ]);
            } catch (\Exception $e) {
                $failed[] = $recipient;
                Log::error('Custom email failed', [
                    'email' => $recipient,
                    'subject' => $subject,
                    'attendee_id' => $attendee->id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        if (count($failed)) {
            return response()->json(['success' => false, 'failed' => $failed, 'message' => 'Some emails failed to send.']);
        }
        return response()->json(['success' => true, 'message' => 'Email(s) sent successfully.']);
    }

    public function uploadFlyer(Request $request, $bookingId)
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

            return redirect()->route('admin.registration')->with('success', 'Flyer uploaded successfully for booking ID ' . $bookingId);
        }

        return back()->with('error', 'Please select a valid image file.');
    }

    public function registerAjax(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'email' => 'required|email',
            'department' => 'required|string|max:255',
            'phone' => 'required|digits:10',
        ]);

        $meeting_code = strtoupper(Str::random(6));
        $attendee = MeetingAttendee::create([
            'booking_id' => $validated['booking_id'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'department' => $validated['department'],
            'phone' => $validated['phone'],
            'meeting_code' => $meeting_code,
            'status' => 'not_present',
        ]);

        $emailSent = false;
        $emailError = null;
        try {
            $this->sendRegistrationEmailFromTemplate($attendee);
            $emailSent = true;
            $attendee->email_status = 'sent';
            $attendee->save();
            Log::info('Registration email sent', [
                'attendee_id' => $attendee->id,
                'email' => $attendee->email,
                'name' => $attendee->name,
                'meeting_code' => $attendee->meeting_code,
                'booking_id' => $attendee->booking_id,
            ]);
        } catch (\Exception $e) {
            $emailError = $e->getMessage();
            $attendee->email_status = 'failed';
            $attendee->save();
            Log::error('Registration email failed', [
                'attendee_id' => $attendee->id,
                'email' => $attendee->email,
                'name' => $attendee->name,
                'meeting_code' => $attendee->meeting_code,
                'booking_id' => $attendee->booking_id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'email_sent' => $emailSent,
            'email_error' => $emailError,
            'attendee' => [
                'name' => $attendee->name,
                'email' => $attendee->email,
                'meeting_code' => $attendee->meeting_code,
                'email_status' => $attendee->email_status,
            ]
        ]);
    }

    public function resendRegistrationEmail($id)
    {
        $attendee = MeetingAttendee::findOrFail($id);

        try {
            $this->sendRegistrationEmailFromTemplate($attendee);
            $attendee->email_status = 'sent';
            $attendee->save();

            Log::info('Registration email resent', [
                'attendee_id' => $attendee->id,
                'email' => $attendee->email,
                'name' => $attendee->name,
                'meeting_code' => $attendee->meeting_code,
                'booking_id' => $attendee->booking_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email resent successfully.',
                'email_status' => 'sent',
            ]);
        } catch (\Exception $e) {
            $attendee->email_status = 'failed';
            $attendee->save();

            Log::error('Registration email resend failed', [
                'attendee_id' => $attendee->id,
                'email' => $attendee->email,
                'name' => $attendee->name,
                'meeting_code' => $attendee->meeting_code,
                'booking_id' => $attendee->booking_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend email: ' . $e->getMessage(),
                'email_status' => 'failed',
            ], 500);
        }
    }

    public function saveMailTemplate(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'email_subject' => 'required|string|max:255',
            'email_body' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:10240',
            'clear_attachments' => 'nullable|boolean',
        ]);

        $attachmentPaths = $booking->email_attachment_paths ?? [];

        if ($request->boolean('clear_attachments')) {
            foreach ($attachmentPaths as $path) {
                if (\Storage::disk('public')->exists($path)) {
                    \Storage::disk('public')->delete($path);
                }
            }
            $attachmentPaths = [];
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $stored = $file->store('mail_templates/' . $booking->id, 'public');
                $attachmentPaths[] = $stored;
            }
        }

        $booking->email_subject = $request->email_subject;
        $booking->email_body = $request->email_body;
        $booking->email_attachment_paths = $attachmentPaths;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Mail template saved successfully.',
            'email_subject' => $booking->email_subject,
            'email_body' => $booking->email_body,
            'attachments' => collect($attachmentPaths)->map(function ($path) {
                return [
                    'path' => $path,
                    'name' => basename($path),
                    'url' => asset('storage/' . $path),
                ];
            })->values(),
        ]);
    }

    private function sendRegistrationEmailFromTemplate(MeetingAttendee $attendee)
    {
        $booking = Booking::find($attendee->booking_id);

        $defaultSubject = 'Meeting Registration Confirmation';
        $defaultBody = '<p>Hello <b>[Name]</b>,</p>'
            . '<p>Your registration was successful!</p>'
            . '<p><b>Your Meeting Code:</b> <span style="color:#42CCC5;">[Meeting Code]</span><br>'
            . 'Please keep this code safe. You will need it to verify your attendance at the meeting.</p>'
            . '<p>Best regards,<br><b>CocoaSpace Team</b></p>';

        $subject = ($booking && $booking->email_subject) ? $booking->email_subject : $defaultSubject;
        $body = ($booking && $booking->email_body) ? $booking->email_body : $defaultBody;

        $personalizedBody = str_replace(
            ['[Name]', '[Meeting Code]'],
            [$attendee->name, $attendee->meeting_code],
            $body
        );

        $attachmentPaths = ($booking && is_array($booking->email_attachment_paths))
            ? $booking->email_attachment_paths
            : [];

        \Mail::send([], [], function ($message) use ($attendee, $subject, $personalizedBody, $attachmentPaths) {
            $message->to($attendee->email)
                ->subject($subject)
                ->html($personalizedBody);

            foreach ($attachmentPaths as $path) {
                $fullPath = storage_path('app/public/' . $path);
                if (file_exists($fullPath)) {
                    $message->attach($fullPath, [
                        'as' => basename($path),
                    ]);
                }
            }
        });
    }
}

