<?php

namespace App\Http\Controllers\MunicipalityApi;

use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BunqService\BunqService;
use App\Helpers\Helper;

class UserController extends Controller
{
    /**
     * Get the user details.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function user(Request $request) {
        return $request->user()->load('permissions');
    }

    /**
     * Get the municipality funds on the bunq.
     *
     * @param  \Illuminate\Http\Request     $request
     * @return \Illuminate\Http\Response
     */
    public function funds(Request $request) {
        if (!$request->user()->hasPermission('budget_manage')) {
            return response([], 401);
        }

        $funds = Helper::BunqService()->getBankAccountBalanceValue();

        $funds_required = floatval((new Voucher())->sum('amount'));
        $funds_required -= (new Transaction())->where([
            'status' => 'success'
        ])->sum('amount');

        return compact('funds', 'funds_required');
    }
}
