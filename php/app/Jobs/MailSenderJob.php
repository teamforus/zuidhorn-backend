<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Mail;

class MailSenderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 10;

    protected $view;
    protected $scope;
    protected $callbackData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($view, $scope, $callbackData)
    {
        $this->view = $view;
        $this->scope = $scope;
        $this->callbackData = $callbackData;
    }

    /**
     * Activate voucher and send voucher details through email
     *
     * @return void
     */
    public function handle()
    {
        $callbackData = $this->callbackData;

        Mail::send(
            $this->view, 
            $this->scope, 
            function($message) use ($callbackData) {
                foreach ($callbackData as $key => $value) {
                    if (gettype($value) != 'array')
                        $value = [$value];

                    call_user_func_array([$message, $key], $value);
                }
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
