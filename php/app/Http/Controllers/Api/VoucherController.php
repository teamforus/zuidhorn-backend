<?php

namespace App\Http\Controllers\Api;

use App\Models\Voucher;
use App\Models\ShopKeeper;
use App\Http\Requests\App\VoucherSubmitRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        if (!$voucher->id)
            return abort(404);

        $response = collect($voucher)->only(['code', 'max_amount']);
        $response['max_amount'] = $voucher->getAvailableFunds();
        $success = true;

        return compact('success', 'response');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(VoucherSubmitRequest $request, Voucher $voucher)
    {
        $response = [];

        $user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($user->id)->first();

        $max_amount = $voucher->getAvailableFunds();

        $amount = $request->input('full_amount') ? $max_amount : $request->input('amount');

        $success = $voucher->logTransaction($shop_keeper->id, $amount);

        return compact('success', 'response');
    }
}