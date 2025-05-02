<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verification_code;

    /**
     * Create a new message instance.
     *
     * @param int $verification_code
     * @return void
     */
    public function __construct($verification_code)
    {
        $this->verification_code = $verification_code;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        return $this->subject('Your Verification Code')
                    ->view('emails.verification_code');
    }
}
