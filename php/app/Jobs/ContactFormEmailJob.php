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

class ContactFormEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 1;
    public $timeout = 120;

    protected $form;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($form)
    {
        $this->form = $form;
    }

    /**
     * Send contact form email
     *
     * @return void
     */
    public function handle()
    {
        $form = $this->form;
        $scope = compact('form');

        Mail::send(
            'emails.contact-form', 
            compact('form'), 
            function ($message) use ($form) {
                $message->to(
                    env('CONTACT_FORM_ADDRESS'), 
                    env('CONTACT_FORM_NAME'));

                if ($form['subject'] == 'tehnical_issuse')
                    $message->to(
                        env('CONTACT_FORM_TECHNICAL_ADDRESS'), 
                        env('CONTACT_FORM_TECHNICAL_NAME'));
                
                $message->subject(
                    'Kindpakket contact form - ' . 
                    ucfirst($form['subject']));
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
