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

use \App\Models\User;
use \App\Models\Voucher;

class VoucherEmailQrCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 120;

    protected $voucher;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }

    /**
     * Activate voucher and send voucher details through email
     *
     * @return void
     */
    public function handle()
    {
        $voucher = $this->voucher;

        Mail::send(
            'emails.voucher-qr-code', 
            compact('voucher'), 
            function ($message) use ($voucher) {
                $message->to($voucher->user->email);
                $message->subject('Voucher QR Code');
            });
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
