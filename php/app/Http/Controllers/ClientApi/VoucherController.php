<?php

namespace App\Http\Controllers\ClientApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;

use App\Services\UIDGeneratorService\Facades\UIDGenerator;

use App\Jobs\VoucherGenerateWalletCodeJob;

use App\Models\Role;
use App\Models\User;
use App\Models\Citizen;
use App\Models\Voucher;

class VoucherController extends Controller
{

    /**
     * Send activation token to email.
     *
     * @param  \Illuminate\Http\Request     $request
     * @param  \App\Models\Voucher          $voucher
     * @return \Illuminate\Http\Response
     */
    public function activateByEmail(
        Request $request, 
        Voucher $voucher
    ) {
        // validate request
        $this->validate($request, [
            'email' => "required|email|confirmed",
            'code'  => "required|exists:vouchers,code"
        ]);

        // email should not be already in the system
        if (User::whereEmail($request->input('email'))->count() > 0) {
            return response(['email' => [
                'Dit E-mailadres is al gebruikt om een Kindpakket account ' . 
                'te activeren. Probeer het nogmaals met een ander' . 
                'E-mailadres.']], 422);
        }

        // code should not be already active
        if ($voucher->user)
            return response(['code' => ['Voucher already active!']], 422);

        // send activation token to the email
        $voucher->sendActivationToken($request->input('email'));

        return [];
    }

    /**
     * Activate the voucher by token received from email.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function activateToken(
        Request $request
    ) {
        // validate request
        $this->validate($request, [
            'activation_token' => "required|exists:vouchers,activation_token"
        ]);

        // target voucher
        $voucher = Voucher::where([
            'activation_token' => $request->input('activation_token')
        ])->first();

        // email should not be already in the system
        if (User::whereEmail($voucher->activation_email)->count() > 0) {
            return response([
                'error' => 'email-busy',
                'message' => 'This email is already in use!',
            ], $status = 401);
        }

        // code should not be already active
        if ($voucher->user) {
            return response([
                'error' => 'voucher-active',
                'message' => 'Voucher already active!',
            ], $status = 401);
        }

        // create new user with random passowrd
        $user = User::create([
            'password'  => Hash::make(UIDGenerator::generate(32, 4)),
            'email'     => $voucher->activation_email,
        ]);

        // attache citizen role
        $user->roles()->attach(
            Role::where('key', 'citizen')->first()->id
        );

        // update voucher user and load model
        $voucher->forceFill([
            'user_id'           => $user->id,
            'activation_email'  => null,
            'activation_token'  => null,
        ])
        ->save();
        
        // create voucher's wallet and add tokens
        VoucherGenerateWalletCodeJob::dispatch(
            $voucher, 
            $voucher->amount
        )->onQueue('high');

        // generate and response the access token
        $access_token = Citizen::create([
            'user_id' => $user->id,
        ])->generateAccessToken();

        return compact('access_token');
    }

    /**
     * Citizen voucher details.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function target(
        Request $request
    ) {
        $voucher = $request->user()->vouchers->first();
        $funds = number_format($voucher->getAvailableFunds(), 2, ',', '.');

        return compact('funds');
    }


    /**
     * Citizen voucher Qr-Code base64.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function getQrCode(
        Request $request
    ) {
        $voucher = $request->user()->vouchers->first();

        $qr_code = base64_encode(QrCode::format('png')
            ->margin(1)->size(300)
            ->generate($voucher->wallet->address));

        return "data:image/png;base64, " . $qr_code;
    }

    /**
     * Send Qr-Code to citizen email.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function sendQrCodeEmail(Request $request) {
        $request->user()->vouchers->first()->emailQrCode();

        return [];
    }
}
