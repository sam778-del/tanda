<?php

namespace Modules\Bills\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Bills\Models\BillTransaction;
use Modules\Bills\Services\BillService;

class RevalidateBillTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected BillTransaction $billTransaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BillTransaction $billTransaction)
    {
        $this->billTransaction = $billTransaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        resolve(BillService::class)->query($this->billTransaction);
    }
}
