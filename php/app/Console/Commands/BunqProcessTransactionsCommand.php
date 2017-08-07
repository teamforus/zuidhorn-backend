<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\BunqProcessTransactionJob;
use App\Models\VoucherTransaction;

class BunqProcessTransactionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bunq:process-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process queued Bunq transactions.';

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
        // reset all not finished transactions older than 10 minutes
        $date = new \DateTime;
        $date = $date->modify('-10 minutes')->format('Y-m-d H:i:s');

        VoucherTransaction::orderBy('id')
        ->where('status', 'processing')
        ->where('updated_at', '<', $date)
        ->update(['status' => 'fail']);

        // get all pending/fail transactions and send to job dispatcher
        $transactions = VoucherTransaction::whereIn(
            'status', ['pending', 'fail']);

        $transactions->each(function($transaction) {
            dispatch(new BunqProcessTransactionJob($transaction));
        });
    }
}
