<?php

namespace Modules\Bills\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Bills\Jobs\RevalidateBillTransaction;
use Modules\Bills\Models\BillTransaction;

class RevalidateBillPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill-payment:revalidate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-query and process all declined/processing bill payment transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $billTransaction = BillTransaction::with(['bill', 'user', 'transaction'])
            ->whereIn('status', [BillTransaction::PENDING, BillTransaction::FAILED])
            ->where('created_at', '>=', now()->subHours(24))
            ->cursor();

        foreach ($billTransaction as $transaction) {
            $this->info("Revalidating bill payment for ".$transaction->transaction_ref);
            RevalidateBillTransaction::dispatch($transaction);
        }
    }
}
