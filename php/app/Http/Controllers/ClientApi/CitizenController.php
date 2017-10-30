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
    public function sendAuthToken(Request $request) {
        $this->validate($request, [
            'email' => 'required|exists:users,email'
        ]);

        $user = User::whereEmail($request->input('email'))->first();
        $citizen = Citizen::whereUserId($user->id)->first();

        $citizenToken = $citizen->generateAuthToken();

        dispatch(new MailSenderJob('emails.voucher-sign-in-email', [
            'citizenToken'  => $citizenToken
        ], [
            'to'            => $user->email,
            'subject'       => 'Kindpakket sign in link'
        ]));

        return [];
    }

    public function signIn(Request $request) {
        $this->validate($request, [
            'token' => [
                'required', 
                Rule::exists('citizen_tokens')->where(function ($query) {
                    $query
                    ->where('used_up', 0)
                    ->where('expires_at', '>', Carbon::now());
                })]
            ]);

        $citizenToken = CitizenToken::whereToken(
            $request->input('token'))->first();

        $citizenToken->update([
            'used_up' => 1,
        ]);
        
        $access_token = $citizenToken->citizen->generateAccessToken();

        return compact('access_token');
    }
}
