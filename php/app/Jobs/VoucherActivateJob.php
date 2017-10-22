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

class VoucherActivateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 120;

    protected $voucher;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Voucher $voucher, $email)
    {
        $this->voucher = $voucher;
        $this->email = $email;
    }

    /**
     * Activate voucher and send voucher details through email
     *
     * @return void
     */
    public function handle()
    {
        $voucher    = $this->voucher;
        $email      = $this->email;

        // Voucher is already activated
        if ($voucher->wallet && $voucher->user)
            return;

        // create new user or use existing
        if (!$user = User::whereEmail($email)->first()) {
            $password = \App\Models\User::generateUid([], 'password', 4, 4);

            $user = User::create([
                'password' => Hash::make($password),
                'email' => $email,
            ]);
        } else {
            $password = false;
        }

        // update voucher user and load model
        $voucher->setOwner($user->id);

        // create voucher wallet, add initial tokkens and 
        // send activation email
        $voucher->generateWallet()->export()->fundTokens($voucher->amount);
        $voucher->emailActivationDetails($password);
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
