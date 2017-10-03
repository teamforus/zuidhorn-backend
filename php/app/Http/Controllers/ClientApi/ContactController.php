<?php

namespace App\Http\Controllers\ClientApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApi\ContactFormRequest;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function postIndex(ContactFormRequest $request) {
        $form = $request->all();

        $scope = compact('form');

        Mail::send('emails.contact-form', $scope, function ($message) use ($form) {
            $receiver = ['valik432@gmail.com', 'Basic contact'];

            if ($form['subject'] == 'tehnical_issuse')
                $receiver = ['valik432@gmail.com', 'Tehnical Issuse contact'];

            $message->to($receiver[0], $receiver[1]);
            $message->subject('Kindpakket contact form - ' . ucfirst($form['subject']));
        });

        return [];
    }
}