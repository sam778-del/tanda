<?php

namespace App\Jobs;

use App\Classes\BulkSms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SmsOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $activationCode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $activationCode)
    {
        $this->user = $user;
        $this->activationCode = $activationCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $text = "Tanda activation pin: " . $this->activationCode;
        $smsSender = new BulkSms($text, $this->user->phone_no);
    }
}
