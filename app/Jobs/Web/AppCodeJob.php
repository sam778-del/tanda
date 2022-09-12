<?php

namespace App\Jobs\Web;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\AppCodeMail;

class AppCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $appCode;
    public $user;
    public $companyName;

    public function __construct(int $appCode, $user, String $companyName)
    {
        $this->appCode = $appCode;
        $this->user = $user;
        $this->companyName = $companyName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new AppCodeMail($this->appCode, $this->user, $this->companyName));
    }
}
