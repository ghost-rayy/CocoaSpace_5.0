<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class RegistrationConfirmation extends Mailable implements ShouldQueue
{
     use Queueable, SerializesModels;

    public $name;
    public $meeting_code;

    public function __construct($name, $meeting_code)
    {
        $this->name = $name;
        $this->meeting_code = $meeting_code;
    }

    public function build()
    {
        return $this->subject('Meeting Registration Confirmation')
                    ->view('emails.registration');
    }
}
