<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\BlockchainApiService\Facades\BlockchainApi;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\Voucher;
use App\Jobs\MailSenderJob;

class VoucherInitializeWalletCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 120;

    protected $voucher;
    protected $tokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Voucher $voucher, $tokens)
    {
        $this->voucher = $voucher;
        $this->tokens = $tokens;
    }

    /**
     * Activate voucher and send voucher details through email
     *
     * @return void
     */
    public function handle()
    {
        $this->voucher->wallet->export()->fundTokens($this->tokens);
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed($exception)
    {

    }
}
