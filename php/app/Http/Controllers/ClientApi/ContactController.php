<?php

namespace App\Http\Controllers\ClientApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApi\ContactFormRequest;
use Illuminate\Support\Facades\Mail;

use App\Jobs\ContactFormEmailJob;

class ContactController extends Controller
{
    public function postIndex(ContactFormRequest $request) {
        dispatch(new ContactFormEmailJob($request->all()));
    }
}