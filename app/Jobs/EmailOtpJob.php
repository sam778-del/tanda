<?php

namespace App\Jobs;

use App\Mail\SendOtpMail;
use App\Resolvers\SmsProviderResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailOtpJob implements ShouldQueue
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
//        Cache::put($this->user->id . "_activation_code", $this->activationCode, now()->addMinutes(15));

        if (!is_null($this->user->email)) {
            Log::info($this->user->email);
            //Send email otp code to the user
            $mailData = (object) [
                'subject' => "Activation OTP Code",
                'body' => "Use this one time password (OTP) {$this->activationCode}  to activate your Tanda Account.",
            ];
            Mail::to($this->user->email)->send(new SendOtpMail($this->user, $mailData));
        }

    }
}
