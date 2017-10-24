<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\BlockchainApiService\Facades\BlockchainApi;

use \App\Models\Transaction;
use \App\Jobs\MailSenderJob;
use \App\Jobs\BlockchainRequestJob;

class BunqProcessTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 2;
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
        if (is_numeric($this->transaction->payment_id)) {
            return $this->transaction->update([
                'status' => 'success'
            ]);
        }

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

        $shopKeeper = $this->transaction->shop_keeper;

        dispatch(new MailSenderJob(
            'emails.voucher-transaction-done', [
                'transaction' => $this->transaction,
            ], [
                'to'        => $this->transaction->voucher->user->email,
                'subject'   => 'Your voucher was used for transaction.',
            ]));

        dispatch(new BlockchainRequestJob(
            'requestFunds', [
                $this->transaction->voucher->wallet->address,
                $shopKeeper->wallet->address,
                $shopKeeper->wallet->passphrase,
                $this->transaction->amount
            ]));
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
