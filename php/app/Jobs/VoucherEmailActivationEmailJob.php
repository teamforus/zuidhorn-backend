<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use \App\Models\User;
use \App\Models\Voucher;

class VoucherEmailActivationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 120;

    protected $voucher;
    protected $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Voucher $voucher, $password)
    {
        $this->voucher = $voucher;
        $this->password = $password;
    }

    /**
     * Activate voucher and send voucher details through email
     *
     * @return void
     */
    public function handle()
    {
        $voucher = $this->voucher;
        $password = $this->password;

        // send message to client
        Mail::send(
            'emails.voucher-activate', 
            compact('voucher', 'password'), 
            function ($message) use ($voucher) {
                $message->to($voucher->user->email);
                $message->subject('Voucher activation');
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
