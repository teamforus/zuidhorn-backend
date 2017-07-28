<?php

namespace App\Http\Controllers\App;

use App\Models\Voucher;
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
        $response = collect($voucher)->only(['code', 'max_amount']);

        $response['max_amount'] = $voucher->getAvailableFunds();

        $success = !!$response;

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

        $max_amount = $voucher->getAvailableFunds();

        $amount = $request->input('full_amount') ? $max_amount : $request->input('amount');

        $success = $voucher->makeTransaction($amount);

        return compact('success', 'response');
    }
}