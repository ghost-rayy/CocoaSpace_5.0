<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\MeetingAttendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;

class RegisterAttendeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bookingId;
    protected $name;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct($bookingId, $name, $email)
    {
        $this->bookingId = $bookingId;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Check if attendee already exists for this booking and email
        $existing = MeetingAttendee::where('booking_id', $this->bookingId)
            ->where('email', $this->email)
            ->first();
        if ($existing) {
            return;
        }

        $meeting_code = strtoupper(Str::random(6));
        $attendee = MeetingAttendee::create([
            'booking_id' => $this->bookingId,
            'name' => $this->name,
            'email' => $this->email,
            'meeting_code' => $meeting_code,
            'status' => 'not_present',
        ]);

        if ($attendee) {
            Mail::to($this->email)->send(new RegistrationConfirmation($this->name, $meeting_code));
        }
    }
} 