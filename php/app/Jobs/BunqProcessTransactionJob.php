<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use \App\Models\Transaction;

class BunqProcessTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 120;
    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->transaction->update([
            'status' => 'processing'
            ]);

        $payment_id = $this->transaction->makeTransaction();

        if (!is_numeric($payment_id))
            throw new \Exception("Error Processing Request: ", 1);

        $this->transaction->update([
            'payment_id' => $payment_id,
            'status'     => 'success',
            ]);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {
        // Send user notification of failure, etc...
        $this->transaction->update([
            'status'     => 'fail',
            ]);
    }
}
