<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $mailData)
    {
        $this->user = $user;
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('kingsley.udenewu@hotmail.com', 'Tanda Solutions')
            ->subject($this->mailData->subject)
            ->view('emails.otp_email');
    }
}
