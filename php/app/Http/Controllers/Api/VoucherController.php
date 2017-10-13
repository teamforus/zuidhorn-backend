<?php

namespace App\Http\Controllers\Api;

use App\Models\Voucher;
use App\Models\ShopKeeper;
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
    public function show(Request $request, Voucher $voucher)
    {
        if (!$voucher->id)
            return response(collect([
                'error' => 'not-found',
                'message' => 'Not found!'
                ]), 404);

        $target_user = $request->user();
        $shop_keeper = ShopKeeper::whereUserId($target_user->id)->first();

        $available_categs = $shop_keeper->categories->pluck('id')->intersect(
            $voucher->budget->categories->pluck('id'));

        if ($available_categs->count() < 1)
            return response(collect([
                'error' => 'no-available-categories',
                'message' => "Shopkeeper don't have categories 
                required by voucher."
                ]), 401);

        $public_key = $voucher->public_key;
        $max_amount = $voucher->getAvailableFunds();

        return compact('public_key', 'max_amount');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Voucher $voucher)
    {
        
    }
}