<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Services\BlockchainApiService\Facades\BlockchainApi;

/**
 * Class BlockchainRequestJob
 * @package App\Jobs
 */
class BlockchainRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 120;

    protected $method;
    protected $args;

    /**
     * Create a new job instance.
     *
     * @param $method
     * @param $args
     */
    public function __construct($method, $args)
    {
        $this->method = BlockchainApi::class . "::" . $method;
        $this->args = $args;
    }

    /**
     * Activate voucher and send voucher details through email
     *
     * @return void
     */
    public function handle()
    {
        call_user_func_array($this->method, $this->args);
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed($exception)
    {

    }
}
