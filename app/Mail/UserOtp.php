<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserOtp extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $activationCode;
    public $user;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($activationCode, $user, $data)
    {
        $this->activationCode = $activationCode;
        $this->$user = $user;
        $this->$data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name');
    }
}
