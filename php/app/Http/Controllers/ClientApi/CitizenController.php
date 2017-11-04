<?php

namespace App\Http\Controllers\ClientApi;

use Carbon\Carbon;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Jobs\MailSenderJob;

use App\Models\User;
use App\Models\Citizen;
use App\Models\CitizenToken;

class CitizenController extends Controller
{
    /**
     * Send auth token by email.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function sendAuthToken(
        Request $request
    ) {
        // validate request
        $this->validate($request, [
            'email' => 'required|exists:users,email'
        ]);

        // get target user by email
        $user = User::whereEmail($request->input('email'))->first();
        $citizen = Citizen::whereUserId($user->id)->first();
        $citizenToken = $citizen->generateAuthToken();

        // send auth token by email
        MailSenderJob::dispatch('emails.voucher-sign-in-email', [
            'citizenToken'  => $citizenToken
        ], [
            'to'            => $user->email,
            'subject'       => 'inloggen op uw kindpakket account'
        ])->onQueue('high');

        return [];
    }

    /**
     * Get access_token by auth token from email.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function signIn(
        Request $request
    ) {
        // validate request
        $this->validate($request, [
            'token' => [
                'required', 
                Rule::exists('citizen_tokens')->where(function ($query) {
                    $query
                    ->where('used_up', 0)
                    ->where('expires_at', '>', Carbon::now());
                })]
            ]);

        // get citize token
        $citizenToken = CitizenToken::whereToken(
            $request->input('token'))->first();

        // mark used
        $citizenToken->update([
            'used_up' => 1,
        ]);
        
        // generate access_token
        $access_token = $citizenToken->citizen->generateAccessToken();

        return compact('access_token');
    }
}
