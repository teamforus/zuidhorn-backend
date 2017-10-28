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

use \App\Models\ShopKeeper;

class ShoKeeperGenerateWalletCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 120;

    protected $shopKeeper;
    protected $ether;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ShopKeeper $shopKeeper, $ether = 1000)
    {
        $this->shopKeeper = $shopKeeper;
        $this->ether = $ether;
    }

    /**
     * Activate voucher and send voucher details through email
     *
     * @return void
     */
    public function handle()
    {
        $shopKeeper->generateWallet()->export()->fundEther($this->ether);
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
