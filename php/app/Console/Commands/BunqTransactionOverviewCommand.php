<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Models\Transaction;
use App\Jobs\MailSenderJob;

class BunqTransactionOverviewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bunq:transaction-overview';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send transaction overview for 
    the current day.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        if (empty(env('MAIL_TRANSACTION_OVERVIEW')))
            die("No transaction overview mail address.\n");

        $transactions = Transaction::whereDate(
            'updated_at', '=', Carbon::today()->toDateString()
        )->get();

        MailSenderJob::dispatch(
            'emails.bunq-transaction-overview', [
                'transactions'     => $transactions
            ], [
                'to'        => [env('MAIL_TRANSACTION_OVERVIEW')],
                'subject'   => 'Bunq - transaction overview - ' . date('Y-m-d'),
            ]
        )->onQueue('high');
    }
}
