<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $attachments;

    public function __construct($name, $attachments = [])
    {
        $this->name = $name;
        $this->attachments = $attachments;
    }

    public function build()
    {
        $email = $this->subject('Verification Successful')
                      ->view('emails.verification_confirmation');
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $file) {
                $email->attach($file['file'], [
                    'as' => $file['name'],
                ]);
            }
        }
        return $email;
    }
} 