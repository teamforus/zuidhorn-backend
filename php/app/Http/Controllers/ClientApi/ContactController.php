<?php

namespace App\Http\Controllers\ClientApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApi\ContactFormRequest;

use App\Jobs\MailSenderJob;

class ContactController extends Controller
{
    /**
     * Send contact form messages.
     *
     * @param  \App\Http\Requests\ClientApi\ContactFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function postIndex(
        ContactFormRequest $request
    ) {
        $form = $request->all();
        $subject = 'Kindpakket contact form - ' . ucfirst($form['subject']);

        $to = [
            env('CONTACT_FORM_ADDRESS'), 
            env('CONTACT_FORM_NAME')
        ];

        if ($request->input('subject') == 'tehnical_issuse') {
            $to = [
                env('CONTACT_FORM_TECHNICAL_ADDRESS'), 
                env('CONTACT_FORM_TECHNICAL_NAME')
            ];
        }

        MailSenderJob::dispatch(
            'emails.contact-form', compact('form'), compact('to', 'subject')
        )->onQueue('high');
    }
}