<?php

namespace App\Http\Controllers\ClientApi;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use Illuminate\Support\Facades\Mail;

class VoucherController extends Controller
{
    public function activate(Request $request, Voucher $voucher)
    {
        $this->validate($request, [
            'email' => "required|email",
            'code' => "required|exists:vouchers,code"
        ]);

        // get or create user
        if ($voucher->user)
            return response(['code' => ['Voucher already active!']], 422);

        // activate voucher and send email
        $voucher->activate($request->input('email'));

        return [];
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
