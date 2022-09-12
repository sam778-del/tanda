<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $appCode;
    public $user;
    public $companyName;

    public function __construct(int $appCode, $user, String $companyName)
    {
        $this->appCOde = $appCode;
        $this->user = $user;
        $this->companyName = $companyName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('view.name')
            ->subject(__('Regarding employee'))
            ->with(['code' => $this->appCode, 'user' => $this->user, 'companyName' =>  $this->companyName]);
    }
}
