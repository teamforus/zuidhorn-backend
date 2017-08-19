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
            return response(collect([
                'message' => 'Voucher not found!'
                ]), 404);

        $code = $voucher->code;
        $max_amount = $voucher->getAvailableFunds();

        return compact('code', 'max_amount');
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
        if (!$voucher->id)
            return response(collect([
                'message' => 'Voucher not found!'
                ]), 404);
        
        $user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($user->id)->first();

        $max_amount = $voucher->getAvailableFunds();

        $amount = $request->input('full_amount') ? $max_amount : $request->input('amount');

        return $voucher->logTransaction($shop_keeper->id, $amount);
    }
}