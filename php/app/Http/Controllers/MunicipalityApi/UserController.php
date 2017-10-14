<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\User;
use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BunqService\BunqService;

class UserController extends Controller
{
    public function user(Request $request) {
        return $request->user()->load('permissions');
    }

    public function funds(Request $request) {
        $bunq_service = new BunqService();

        $response = $bunq_service->getMonetaryAccounts()->Response[0];
        $funds = floatval($response->MonetaryAccountBank->balance->value);

        $funds_required = floatval(Voucher::sum('amount'));
        $funds_required -= Transaction::whereStatus('success')->sum('amount');

        return compact('funds', 'funds_required');
    }
}
