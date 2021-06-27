<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invited_by;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invited_by)
    {

        $this->invited_by = $invited_by;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You have got an invite from AALP Photos')->view('emails.invitation');
    }
}
