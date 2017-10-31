<?php

namespace App\Http\Controllers\ClientApi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;

use App\Jobs\VoucherGenerateWalletCodeJob;

use App\Models\Role;
use App\Models\User;
use App\Models\Citizen;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function activateByEmail(Request $request, Voucher $voucher)
    {
        $this->validate($request, [
            'email' => "required|email|confirmed",
            'code'  => "required|exists:vouchers,code"
        ]);

        if (User::whereEmail($request->input('email'))->count() > 0)
            return response(['email' => [
                'Dit E-mailadres is al gebruikt om een Kindpakket account ' . 
                'te activeren. Probeer het nogmaals met een ander' . 
                'E-mailadres.']], 422);

        // get or create user
        if ($voucher->user)
            return response(['code' => ['Voucher already active!']], 422);

        // activate voucher and send email
        $voucher->activateByEmail($request->input('email'));

        return [];
    }

    public function activateToken(Request $request) {
        $this->validate($request, [
            'activation_token' => "required|exists:vouchers,activation_token"
        ]);

        $voucher = Voucher::whereActivationToken(
            $request->input('activation_token')
        )->first();

        if ($voucher->user)
            return response([
                'error' => 'voucher-active',
                'message' => 'Voucher already active!',
            ], $status = 401);

        if (User::whereEmail($voucher->activation_email)->count() > 0)
            return response([
                'error' => 'email-busy',
                'message' => 'This email is already in use!',
            ], $status = 401);

        $password = \App\Models\User::generateUid([], 'password', 4, 16);

        $user = User::create([
            'password'  => Hash::make($password),
            'email'     => $voucher->activation_email,
        ]);

        $user->roles()->attach(
            Role::where('key', 'citizen')->first()->id
        );

        // update voucher user and load model
        $voucher->setOwner($user->id);
        
        // create voucher's wallet and add tokens
        dispatch(new VoucherGenerateWalletCodeJob($voucher, $voucher->amount));

        $access_token = Citizen::create([
            'user_id' => $user->id,
        ])->generateAccessToken();

        return compact('access_token');
    }

    public function target(Request $request)
    {
        $voucher = $request->user()->vouchers->first();
        $funds = number_format($voucher->getAvailableFunds(), 2, ',', '.');

        return compact('funds');
    }

    public function getQrCode(Request $request) {
        $voucher = $request->user()->vouchers->first();

        $qr_code = base64_encode(QrCode::format('png')
            ->margin(1)->size(300)
            ->generate($voucher->wallet->address));

        return "data:image/png;base64, " . $qr_code;
    }

    public function sendQrCodeEmail(Request $request) {
        $request->user()->vouchers->first()->emailQrCode();

        return [];
    }
}
