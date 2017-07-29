<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Voucher;
use App\Models\User;
use App\Services\BunqService\BunqService;

class TestController extends Controller
{
    public function getTest(Request $request)
    {
        $voucher = Voucher::whereCode('VIES-2F9M-J8RR-TC5W')->first();
        $response = $voucher->user_buget->transactions->sum('amount');

        return compact('response');
    }
}
